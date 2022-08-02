from __future__ import print_function
import sys
from labjack import ljm
import time
from datetime import datetime
import psycopg2

arrAIN = [0] * 8

try:
    # connect into database
    mydb = psycopg2.connect(
        host="localhost", database="trudas_db", user="postgres", password="root", )
    mycursor = mydb.cursor()

    # insert loop
    while True:
        # query get labjack ip
        getiplabjack = (
            """SELECT labjack_ip FROM sensors WHERE is_deleted = '0' GROUP BY labjack_ip""")
        mycursor.execute(getiplabjack)
        labjack_ip = mycursor.fetchall()
        # print(labjack_ip)
        # query get all data
        getdata = ("""SELECT * FROM sensors WHERE is_deleted = '0'""")
        mycursor.execute(getdata)
        data = mycursor.fetchall()

        # date now
        now = datetime.now()
        day_of_number = now.strftime("%d-%H:%M:%S")
        day_time = now.strftime("%Y%m%d%M")
        timestamp = now.strftime("%Y-%m-%d %H:%M:%S")
        # print(day_time)

        # schedule auto backup [rename table sensor_values and create new]
        # if(day_of_number == '01-00:00:00' or day_of_number == '16-00:00:00'):
        if(day_of_number == '01-00:01:00' or day_of_number == '16-00:01:00'):
            sql_update_backup_status_on = (
                "UPDATE backup SET is_backup = '1'")
            mycursor.execute(sql_update_backup_status_on)
            mydb.commit()

        # check backup status
        sql_backup_status = (
            "SELECT is_backup FROM backup")
        mycursor.execute(sql_backup_status)
        is_backup = mycursor.fetchone()[0]

        # condition backup status
        if(is_backup == 0):
            # print(is_backup)
            # check labjack ip
            for ip in labjack_ip:
                # status labjack
                # print("[V] Labjack " + ip[0] + " CONNECTED")
                # open labjack from ip
                try:
                    # open_labjack_ip = ljm.openS("ANY", "ANY", ip[0])
                    open_labjack_ip = ljm.openS("ANY", "ANY", ip[0])
                    # connect to labjack from ip and get data ain and insert data every second
                    for ain in data:
                        if(ain[1] == ip[0]):
                            # connect to labjack and get data ain
                            AIN = ljm.eReadName(
                                open_labjack_ip, "AIN" + str(ain[2]))
                            arrAIN[ain[2]] = AIN
                            # trying to insert data into sensor_values table
                            try:
                                if(ain[17] == 0):
                                    print('NOT REFERENCE_S')
                                    # query insert
                                    sql = (
                                        "INSERT INTO sensor_values (instrument_param_id, data, voltage, is_averaged, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(eval(ain[6])) + "','" + str(AIN) + "','0','" + timestamp + "')")
                                    mycursor.execute(sql)
                                    mydb.commit()
                                    print('sensor values inserted')
                                    # check data sensor_value_logs
                                    sql_select_log = (
                                        "SELECT COUNT(*) FROM sensor_value_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                                    mycursor.execute(sql_select_log)
                                    mydb.commit()
                                    labjack_value_id = mycursor.fetchone()[0]
                                    print('sensor_value_logs counted')
                                    if(labjack_value_id != 0):
                                        # update sensor_value_logs
                                        sql_update_log = (
                                            "UPDATE sensor_value_logs SET data = '" + str(eval(ain[6])) + "', voltage = '" + str(AIN) + "' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                                        mycursor.execute(sql_update_log)
                                        mydb.commit()
                                        print('update sensor logs')
                                    else:
                                        # insert sensor_value_logs
                                        sql_insert_log = (
                                            "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(eval(ain[6])) + "','" + str(AIN) + "','" + timestamp + "')")
                                        mycursor.execute(sql_insert_log)
                                        mydb.commit()
                                        print('insert sensor logs')
                                else:
                                    # start new condition
                                    sql_reference = (
                                        "SELECT formula FROM reference_s WHERE instrument_param_id = '" + str(ain[3]) + "' AND range_start <= '" + str(eval(ain[6])) + "' AND range_end >= '" + str(eval(ain[6])) + "'")
                                    mycursor.execute(sql_reference)
                                    is_reference = mycursor.fetchone()[0]
                                    print('IS REFERENCE_S')
                                    # query insert
                                    sql = (
                                        "INSERT INTO sensor_values (instrument_param_id, data, voltage, is_averaged, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(eval(is_reference)) + "','" + str(AIN) + "','0','" + timestamp + "')")
                                    mycursor.execute(sql)
                                    mydb.commit()
                                    print('sensor values inserted')
                                    # check data sensor_value_logs
                                    sql_select_log = (
                                        "SELECT COUNT(*) FROM sensor_value_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                                    mycursor.execute(sql_select_log)
                                    mydb.commit()
                                    labjack_value_id = mycursor.fetchone()[0]
                                    print('sensor_value_logs counted')
                                    if(labjack_value_id != 0):
                                        # update sensor_value_logs
                                        sql_update_log = (
                                            "UPDATE sensor_value_logs SET data = '" + str(eval(is_reference)) + "', voltage = '" + str(AIN) + "' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                                        mycursor.execute(sql_update_log)
                                        mydb.commit()
                                        print('update sensor logs')
                                    else:
                                        # insert sensor_value_logs
                                        sql_insert_log = (
                                            "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(eval(is_reference)) + "','" + str(AIN) + "','" + timestamp + "')")
                                        mycursor.execute(sql_insert_log)
                                        mydb.commit()
                                        print('insert sensor logs')
                                    # end new condition
                            except Exception as e:
                                # failed result
                                print('insert failed')
                except Exception as e:
                    print(e)
        else:
            # print(is_backup)
            # rename table sensor_values
            sql_rename_table = (
                "ALTER TABLE sensor_values RENAME TO sensor_values" + str(day_time) + "")
            mycursor.execute(sql_rename_table)
            mydb.commit()

            # create table sensor_values
            sql_create_table = (
                """CREATE TABLE sensor_values (id BIGSERIAL primary key, instrument_param_id INT NOT NULL default 0, data double precision default 0, voltage double precision default 0, is_averaged smallint default 0, xtimestamp timestamp)""")
            mycursor.execute(sql_create_table)
            mydb.commit()

            # update backup status
            sql_update_backup_status_off = (
                "UPDATE backup SET is_backup = '0'")
            mycursor.execute(sql_update_backup_status_off)
            mydb.commit()
        time.sleep(1)
except Exception as e:
    print("[X]  Labjack " + e)
