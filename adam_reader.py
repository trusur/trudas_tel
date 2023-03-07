from __future__ import print_function
import sys
from datetime import datetime
import psycopg2
import pymodbus
from pymodbus.constants import Endian
from pymodbus.payload import BinaryPayloadDecoder
from pymodbus.client.sync import ModbusTcpClient
import requests
import json
import time
import math
from random import randint

try:
    # connect into database
    mydb = psycopg2.connect(
        host="localhost", database="trudas_db", user="postgres", password="root", )
    mycursor = mydb.cursor()
    getdata = (
        "SELECT analyzer_ip FROM configurations WHERE id = 1")
    mycursor.execute(getdata)
    analyzer_ip = mycursor.fetchone()[0]
    # connect into adam
    client = ModbusTcpClient(analyzer_ip, port=502)  # Specify the port.
    connection = client.connect()
    #print(connection)
    #sys.exit()
    def adamNotConnected(value_error):
        # get data sensors
        getdata = (
            "SELECT * FROM sensors WHERE instrument_param_id <= 10 AND is_show = '1' AND is_deleted = '0' ORDER BY id ASC")
        mycursor.execute(getdata)
        data = mycursor.fetchall()
        for ain in data:
            if(ain[7] == 0):
                sql_insert_data = (
                    "INSERT INTO sensor_values (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','0','"+str(value_error)+"','" + timestamp + "')")
                mycursor.execute(sql_insert_data)
                mydb.commit()
                # print('insert sensor values')

                log_check = (
                    "SELECT COUNT(*) FROM sensor_value_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                mycursor.execute(log_check)
                reader_value = mycursor.fetchone()[0]
                if(reader_value != 0):
                    # update sensor_value_logs
                    sql_update_log = (
                        "UPDATE sensor_value_logs SET data = '0', voltage = '"+str(value_error)+"' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                    mycursor.execute(sql_update_log)
                    mydb.commit()
                    # print('update sensor logs')
                else:
                    # insert sensor_value_logs
                    sql_insert_log = (
                        "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','0','"+str(value_error)+"','" + timestamp + "')")
                    mycursor.execute(sql_insert_log)
                    mydb.commit()
                    # print('insert sensor logs')
            else:
                # start new condition
                sql_reference = (
                    "SELECT formula FROM reference_s WHERE instrument_param_id = '" + str(ain[3]) + "' AND range_start <= '" + str(value_data) + "' AND range_end >= '" + str(value_data) + "'")
                mycursor.execute(sql_reference)
                is_reference = mycursor.fetchone()[0]
                # print('IS REFERENCE_S')

                sql_insert_data = (
                    "INSERT INTO sensor_values (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','0','"+str(value_error)+"','" + timestamp + "')")
                mycursor.execute(sql_insert_data)
                mydb.commit()
                # print('insert sensor values')

                log_check = (
                    "SELECT COUNT(*) FROM sensor_value_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                mycursor.execute(log_check)
                reader_value = mycursor.fetchone()[0]
                if(reader_value != 0):
                    # update sensor_value_logs
                    sql_update_log = (
                        "UPDATE sensor_value_logs SET data = '0', voltage = '"+str(value_error)+"' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                    mycursor.execute(sql_update_log)
                    mydb.commit()
                    # print('update sensor logs')
                else:
                    # insert sensor_value_logs
                    sql_insert_log = (
                        "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','0','"+str(value_error)+"','" + timestamp + "')")
                    mycursor.execute(sql_insert_log)
                    mydb.commit()
                    # print('insert sensor logs')
    while True:
        # date
        now = datetime.now()
        day_of_number = now.strftime("%d-%H:%M:%S")
        day_time = now.strftime("%Y%m%d%M")
        timestamp = now.strftime("%Y-%m-%d %H:%M:%S")

        try:
            # check connection to adam
            if connection:
                # get ain from adam
                request = client.read_holding_registers(0, 8)
                v_data = request.registers
                #print(v_data)
                # check is rca
                getconfig = (
                    "SELECT is_rca, oxygen_reference FROM configurations")
                mycursor.execute(getconfig)
                config = mycursor.fetchone()

                # get data sensors
                getdata = (
                    "SELECT * FROM sensors WHERE instrument_param_id <= 10 AND is_show = '1' AND is_deleted = '0' ORDER BY id ASC")
                mycursor.execute(getdata)
                data = mycursor.fetchall()
                # loop data sensors
                for ain in data:
                    ma = 0 if ain[2] == -1 else (2.44144E-4*v_data[ain[2]]) + 4
                    # round((2.44144E-4*v_data[0]) + 4, 3) | example code of formula
                    value_data = eval(ain[6])
                    
                    if(ain[7] == 0):
                        sql_insert_data = (
                            "INSERT INTO sensor_values (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(value_data) + "','" + str(ma) + "','" + timestamp + "')")
                        mycursor.execute(sql_insert_data)
                        mydb.commit()
                        # print('insert sensor values')

                        log_check = (
                            "SELECT COUNT(*) FROM sensor_value_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                        mycursor.execute(log_check)
                        reader_value = mycursor.fetchone()[0]
                        if(reader_value != 0):
                            # update sensor_value_logs
                            sql_update_log = (
                                "UPDATE sensor_value_logs SET data = '" + str(value_data) + "', voltage = '" + str(ma) + "' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                            mycursor.execute(sql_update_log)
                            mydb.commit()
                            # print('update sensor logs')
                        else:
                            # insert sensor_value_logs
                            sql_insert_log = (
                                "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(value_data) + "','" + str(value_data) + "','" + timestamp + "')")
                            mycursor.execute(sql_insert_log)
                            mydb.commit()
                            # print('insert sensor logs')

                        # is rca mode active
                        if(config[0] == 1):
                            # insert data
                            if(ain[8] > 0):
                                getdatao2 = (
                                    "SELECT formula FROM sensors WHERE extra_parameter = '1'")
                                mycursor.execute(getdatao2)
                                datao2 = mycursor.fetchone()[0]
                                formula = value_data
                                if(ain[9] == 1):
                                    not_correction = formula
                                    correction = round(formula *
                                                       (21 - config[1]) / (21 - eval(datao2)), 2)
                                else:
                                    not_correction = formula
                                    correction = formula
                                # insert sensor_value_rca
                                sql_insert_log = (
                                    "INSERT INTO sensor_value_rca (instrument_param_id, data, data_correction, voltage, unit_id, xtimestamp) VALUES ('" + str(ain[3]) + "','"+str(not_correction)+"','" + str(correction) + "','" + str(value_data) + "','"+str(ain[5])+"','" + timestamp + "')")
                                mycursor.execute(sql_insert_log)
                                mydb.commit()
                                # print('insert sensor RCA')

                                # rca logs
                                rca_log_check = (
                                    "SELECT COUNT(*) FROM sensor_value_rca_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                                mycursor.execute(rca_log_check)
                                rca_reader_value = mycursor.fetchone()[
                                    0]
                                if(rca_reader_value != 0):
                                    # update sensor_value_rca_logs
                                    sql_update_log = (
                                        "UPDATE sensor_value_rca_logs SET data = '" + str(correction) + "', voltage = '" + str(formula) + "' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                                    mycursor.execute(sql_update_log)
                                    mydb.commit()
                                    # print('update sensor rca logs')
                                else:
                                    # insert sensor_value_rca_logs
                                    sql_insert_log = (
                                        "INSERT INTO sensor_value_rca_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(correction) + "','" + str(formula) + "','" + timestamp + "')")
                                    mycursor.execute(sql_insert_log)
                                    mydb.commit()
                                    # print('insert sensor rca logs')
                    else:
                        # start new condition
                        sql_reference = (
                            "SELECT formula FROM reference_s WHERE instrument_param_id = '" + str(ain[3]) + "' AND range_start <= '" + str(value_data) + "' AND range_end >= '" + str(value_data) + "'")
                        mycursor.execute(sql_reference)
                        is_reference = mycursor.fetchone()[0]
                        # print('IS REFERENCE_S')

                        sql_insert_data = (
                            "INSERT INTO sensor_values (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(eval(is_reference)) + "','" + str(value_data) + "','" + timestamp + "')")
                        mycursor.execute(sql_insert_data)
                        mydb.commit()
                        # print('insert sensor values')

                        log_check = (
                            "SELECT COUNT(*) FROM sensor_value_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                        mycursor.execute(log_check)
                        reader_value = mycursor.fetchone()[0]
                        if(reader_value != 0):
                            # update sensor_value_logs
                            sql_update_log = (
                                "UPDATE sensor_value_logs SET data = '" + str(eval(is_reference)) + "', voltage = '" + str(value_data) + "' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                            mycursor.execute(sql_update_log)
                            mydb.commit()
                            # print('update sensor logs')
                        else:
                            # insert sensor_value_logs
                            sql_insert_log = (
                                "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(eval(is_reference)) + "','" + str(value_data) + "','" + timestamp + "')")
                            mycursor.execute(sql_insert_log)
                            mydb.commit()
                            # print('insert sensor logs')

                        # is rca mode active
                        if(config[0] == 1):
                            # insert data
                            if(ain[8] > 0):
                                getdatao2 = (
                                    "SELECT formula FROM sensors WHERE extra_parameter = '1'")
                                mycursor.execute(getdatao2)
                                datao2 = mycursor.fetchone()[0]
                                # insert sensor_value_rca
                                sql_insert_log = (
                                    "INSERT INTO sensor_value_rca (instrument_param_id, data, data_correction, voltage, unit_id, xtimestamp) VALUES ('" + str(ain[3]) + "','"+str(not_correction)+"','" + str(correction) + "','" + str(value_data) + "','"+str(ain[5])+"','" + timestamp + "')")
                                mycursor.execute(sql_insert_log)
                                mydb.commit()
                                # print('insert sensor RCA')

                                # rca logs
                                rca_log_check = (
                                    "SELECT COUNT(*) FROM sensor_value_rca_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                                mycursor.execute(rca_log_check)
                                rca_reader_value = mycursor.fetchone()[
                                    0]
                                if(rca_reader_value != 0):
                                    # update sensor_value_rca_logs
                                    sql_update_log = (
                                        "UPDATE sensor_value_rca_logs SET data = '0', voltage = '"+str(value_data)+"' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                                    mycursor.execute(sql_update_log)
                                    mydb.commit()
                                    # print('update sensor rca logs')
                                else:
                                    # insert sensor_value_rca_logs
                                    sql_insert_log = (
                                        "INSERT INTO sensor_value_rca_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','0','"+str(value_data)+"','" + timestamp + "')")
                                    mycursor.execute(sql_insert_log)
                                    mydb.commit()
                                    # print('insert sensor rca logs')
        except Exception as e:
            print(e)
            if connection:
                adamNotConnected(-111)
            else:
                adamNotConnected(-222)
        time.sleep(1)
except Exception as e:
    print(e)
    do_nothing = ''
