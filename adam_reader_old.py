from __future__ import print_function
import sys
from datetime import datetime
import psycopg2
import pymodbus
from pymodbus.pdu import ModbusRequest
from pymodbus.client.sync import ModbusSerialClient as ModbusClient
from pymodbus.transaction import ModbusRtuFramer
import requests
import json
import time
from random import randint

try:
    # connect into database
    mydb = psycopg2.connect(
        host="localhost", database="trudas_db", user="postgres", password="root", )
    mycursor = mydb.cursor()

    #port = '/dev/ttyADAM'
    port = 'COM8'
    baudrate = 9600
    client = ModbusClient(
                    method='rtu', port=port, baudrate=baudrate, parity='N', timeout=1
                )
    #print(client.connect())
    #print(client.read_holding_registers(0, 8, unit=1))
    #exit()
    #def read_value(client, data_value):
    #    read = client.read_holding_registers(0, 8, unit=1)
    #    read_register = read.registers[data_value] * 20 / 4095
    #    voltage_value = float(read_register * 20 / 4095)
    #    return voltage_value
    
    def adamNotConnected(value_error):
        # get data sensors
        getdata = (
            "SELECT * FROM sensors WHERE is_deleted = '0' AND id < 9 ORDER BY id ASC")
        mycursor.execute(getdata)
        data = mycursor.fetchall()
        for ain in data:
            if(ain[7] == 0):
                sql_insert_data = (
                    "INSERT INTO sensor_values (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','0','"+str(value_error)+"','" + timestamp + "')")
                mycursor.execute(sql_insert_data)
                mydb.commit()
                #print('insert sensor values')

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
                    #print('update sensor logs')
                else:
                    # insert sensor_value_logs
                    sql_insert_log = (
                        "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','0','"+str(value_error)+"','" + timestamp + "')")
                    mycursor.execute(sql_insert_log)
                    mydb.commit()
                    #print('insert sensor logs')
            else:
                # start new condition
                sql_reference = (
                    "SELECT formula FROM reference_s WHERE instrument_param_id = '" + str(ain[3]) + "' AND range_start <= '" + str(value_data) + "' AND range_end >= '" + str(value_data) + "'")
                mycursor.execute(sql_reference)
                is_reference = mycursor.fetchone()[0]
                #print('IS REFERENCE_S')

                sql_insert_data = (
                    "INSERT INTO sensor_values (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','0','"+str(value_error)+"','" + timestamp + "')")
                mycursor.execute(sql_insert_data)
                mydb.commit()
                #print('insert sensor values')

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
                    #print('update sensor logs')
                else:
                    # insert sensor_value_logs
                    sql_insert_log = (
                        "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','0','"+str(value_error)+"','" + timestamp + "')")
                    mycursor.execute(sql_insert_log)
                    mydb.commit()
                    #print('insert sensor logs')

    while True:
        # connection = True
        #connection = client.connect()
        #print(connection)
        # try to connect to adam
        # date now
        now = datetime.now()
        day_of_number = now.strftime("%d-%H:%M:%S")
        day_time = now.strftime("%Y%m%d%M")
        timestamp = now.strftime("%Y-%m-%d %H:%M:%S")

        #if(connection == True):
        try:
            #print("sidik")
            read = client.read_holding_registers(0, 8, unit=1)
            #print(read)
            
            #round((read.registers[0] * 20 / 4095), 2)
            # print(read.registers)
            # reverse value to origin voltage
            # select configurations extra_parameter
            getconfig = (
                "SELECT is_rca, oxygen_reference FROM configurations")
            mycursor.execute(getconfig)
            config = mycursor.fetchone()
            # get data sensors
            getdata = (
                "SELECT * FROM sensors WHERE is_deleted = '0' AND id < 9 ORDER BY id ASC")
            mycursor.execute(getdata)
            data = mycursor.fetchall()
            for ain in data:
                mA = read.registers[ain[2]] * 20 / 4095
                if(eval(ain[6]) > 2.44 or ain[3] == 6):
                    value_data = eval(ain[6])
                else:
                    value_data = 0
                #value_data = eval(ain[6])
                # get value from reader
                # value = read.registers[ain[2]]  # reading register 30223
                # reverse value to origin voltage
                # voltage_value = float(value * 20 / 4095)
                # print(voltage_value)
                # insert sensor_values
                if(ain[7] == 0):
                    sql_insert_data = (
                        "INSERT INTO sensor_values (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(value_data) + "','" + str(mA) + "','" + timestamp + "')")
                    mycursor.execute(sql_insert_data)
                    mydb.commit()
                    #print('insert sensor values')

                    log_check = (
                        "SELECT COUNT(*) FROM sensor_value_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                    mycursor.execute(log_check)
                    reader_value = mycursor.fetchone()[0]
                    if(reader_value != 0):
                        # update sensor_value_logs
                        sql_update_log = (
                            "UPDATE sensor_value_logs SET data = '" + str(value_data) + "', voltage = '" + str(mA) + "' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                        mycursor.execute(sql_update_log)
                        mydb.commit()
                        #print('update sensor logs')
                    else:
                        # insert sensor_value_logs
                        sql_insert_log = (
                            "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(value_data) + "','" + str(mA) + "','" + timestamp + "')")
                        mycursor.execute(sql_insert_log)
                        mydb.commit()
                        #print('insert sensor logs')

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
                            #print('insert sensor RCA')

                            # rca logs
                            rca_log_check = (
                                "SELECT COUNT(*) FROM sensor_value_rca_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                            mycursor.execute(rca_log_check)
                            rca_reader_value = mycursor.fetchone()[0]
                            if(rca_reader_value != 0):
                                # update sensor_value_rca_logs
                                sql_update_log = (
                                    "UPDATE sensor_value_rca_logs SET data = '" + str(correction) + "', voltage = '" + str(formula) + "' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                                mycursor.execute(sql_update_log)
                                mydb.commit()
                                #print('update sensor rca logs')
                            else:
                                # insert sensor_value_rca_logs
                                sql_insert_log = (
                                    "INSERT INTO sensor_value_rca_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(correction) + "','" + str(formula) + "','" + timestamp + "')")
                                mycursor.execute(sql_insert_log)
                                mydb.commit()
                                #print('insert sensor rca logs')
                else:
                    # start new condition
                    sql_reference = (
                        "SELECT formula FROM reference_s WHERE instrument_param_id = '" + str(ain[3]) + "' AND range_start <= '" + str(value_data) + "' AND range_end >= '" + str(value_data) + "'")
                    mycursor.execute(sql_reference)
                    is_reference = mycursor.fetchone()[0]
                    #print('IS REFERENCE_S')

                    sql_insert_data = (
                        "INSERT INTO sensor_values (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(eval(is_reference)) + "','" + str(mA) + "','" + timestamp + "')")
                    mycursor.execute(sql_insert_data)
                    mydb.commit()
                    #print('insert sensor values')

                    log_check = (
                        "SELECT COUNT(*) FROM sensor_value_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                    mycursor.execute(log_check)
                    reader_value = mycursor.fetchone()[0]
                    if(reader_value != 0):
                        # update sensor_value_logs
                        sql_update_log = (
                            "UPDATE sensor_value_logs SET data = '" + str(eval(is_reference)) + "', voltage = '" + str(mA) + "' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                        mycursor.execute(sql_update_log)
                        mydb.commit()
                        #print('update sensor logs')
                    else:
                        # insert sensor_value_logs
                        sql_insert_log = (
                            "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(eval(is_reference)) + "','" + str(mA) + "','" + timestamp + "')")
                        mycursor.execute(sql_insert_log)
                        mydb.commit()
                        #print('insert sensor logs')

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
                                "INSERT INTO sensor_value_rca (instrument_param_id, data, data_correction, voltage, unit_id, xtimestamp) VALUES ('" + str(ain[3]) + "','"+str(not_correction)+"','" + str(correction) + "','" + str(mA) + "','"+str(ain[5])+"','" + timestamp + "')")
                            mycursor.execute(sql_insert_log)
                            mydb.commit()
                            #print('insert sensor RCA')

                            # rca logs
                            rca_log_check = (
                                "SELECT COUNT(*) FROM sensor_value_rca_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                            mycursor.execute(rca_log_check)
                            rca_reader_value = mycursor.fetchone()[0]
                            if(rca_reader_value != 0):
                                # update sensor_value_rca_logs
                                sql_update_log = (
                                    "UPDATE sensor_value_rca_logs SET data = '0', voltage = '"+str(value_error)+"' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                                mycursor.execute(sql_update_log)
                                mydb.commit()
                                #print('update sensor rca logs')
                            else:
                                # insert sensor_value_rca_logs
                                sql_insert_log = (
                                    "INSERT INTO sensor_value_rca_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','0','"+str(value_error)+"','" + timestamp + "')")
                                mycursor.execute(sql_insert_log)
                                mydb.commit()
                                #print('insert sensor rca logs')
        except Exception as e:
            print(e)
            if(client.connect() == True):
                adamNotConnected(-111)
            else:
                adamNotConnected(-222)
            do_nothing = ''
            #print('no respon from adam',e)
        #else:
        #    adamNotConnected(-111)
        #    do_nothing = ''
        #    print('DISCONNECTED')
        time.sleep(2)
except Exception as e:
    print(e)
    do_nothing = ''
    #print("[X] Database not connected ",e)
