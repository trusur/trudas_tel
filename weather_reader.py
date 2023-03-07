from __future__ import print_function
from pyvantagepro import VantagePro2
import sys
import time
import psycopg2
# import db_connect

is_WS_connect = False

try:
    # connect into database
    mydb = psycopg2.connect(
        host="localhost", database="trudas_db", user="postgres", password="root", )
    mycursor = mydb.cursor()
    

    def connect_ws():
        global is_WS_connect
        try:
            COM_WS = VantagePro2.from_url(
                "serial:%s:%s:8N1" % ('COM5', 19200))
            ws_data = COM_WS.get_current_data()
            WS = ws_data.to_csv(';', False)
            #print("[V] VantagePro2 " + 'COM5' + " CONNECTED")
            is_WS_connect = True
            return COM_WS
        except:
            is_WS_connect = False
            return None

    try:
        while True:
            try:
                if(not is_WS_connect):
                    COM_WS = connect_ws()

                ws_data = COM_WS.get_current_data()
                WS = ws_data.to_csv(';', False)
                value_split = WS[0:149].split(';')
                #print(ws_data.to_csv(';', False))
                #exit()
                getdata = ("SELECT * FROM sensors WHERE is_deleted = '0' AND id > 8 ORDER BY id ASC")
                mycursor.execute(getdata)
                data = mycursor.fetchall()
                
                for ain in data:
                    #print(round(float(value_split[8])))
                    value_formula = eval(ain[6])
                    #print(value_formula)
                    #
                    log_check = (
                        "SELECT COUNT(*) FROM sensor_value_logs WHERE instrument_param_id = '" + str(ain[3]) + "'")
                    mycursor.execute(log_check)
                    reader_value = mycursor.fetchone()[0]
                    if(reader_value != 0):
                        sql_update_log = ("UPDATE sensor_value_logs SET data = '" + str(value_formula) + "', voltage = '" + str(value_formula) + "' WHERE instrument_param_id = '" + str(ain[3]) + "'")
                        mycursor.execute(sql_update_log)
                        mydb.commit()
                    else:
                        sql_insert_log = (
                            "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, xtimestamp) VALUES ('" + str(ain[3]) + "','" + str(value_formula) + "','" + str(value_formula) + "','" + timestamp + "')")
                        mycursor.execute(sql_insert_log)
                        mydb.commit()
                # update_sensor_value(str(sys.argv[1]), WS[0:149])
                #value_split = WS[0:149].split(';')
                #print(WS.split(';'))
                #print("Wind Direction : ", round(float(value_split[8])))
                #print("Wind Speed :", round(float(value_split[6])))
                #print("Temperature : ", round(float(value_split[5])))
                #print("Barometer : ", round(float(value_split[2]) * 33.8639, 2))
                #print("Humidity : ", round((float(value_split[9]) - 32) * 5/9, 1))
                #print("Solar Radiation :", round(float(value_split[12])))
                #print("Rain Intensity :", round(float(value_split[15])))
                #print(WS[0])
            except Exception as e2:
                is_WS_connect = False
                #print("Reconnect WS Davis")
                # update_sensor_value(
                #     str(sys.argv[1]), ';0;0;0;0;0;0;0;0;0;0;0;0;0.0;0;0;0;0')
            time.sleep(1)

    except Exception as e:
        do_nothing = ''
        #print(e)
except Exception as e:
    do_nothing = ''
    #print(e)
