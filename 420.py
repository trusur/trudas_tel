from pymodbus.client.sync import ModbusSerialClient as ModbusClient
import requests
import json
import time
from time import sleep


port = '/dev/ttyANALOG'
# port = 'COM5'
baudrate = 9600
client = ModbusClient(
    method='rtu', port=port, baudrate=baudrate, parity='N', timeout=1
)
# client = serial.Serial(port, baudrate, timeout=1)
url = "http://localhost/trucems/public/api/"
get_payload = {}
headers = {
    'Content-Type': 'application/x-www-form-urlencoded'
}
connection = client.connect()

while True:
    # connection = True
    connection = client.connect()

    if(connection == True):
        try:
            response_plc = requests.request(
                "GET", url + "plc", headers=headers)

            json_plc = json.loads(response_plc.text)
            alarm = json_plc["data"]["alarm"]

            if(alarm == 0):
                result = client.write_coils(0, [1], unit=1)
            else:
                result = client.write_coils(0, [0], unit=1)

            response_value = requests.request(
                "GET", url + "sensor-value-logs", headers=headers, data=get_payload)
            json_get = json.loads(response_value.text)
            for dv in json_get['data']:
				if(dv['sensor_id'] != 4):
                    value = float(dv['value'])
                    #value = 15
                    #print(value)
                    sensor_id = dv['sensor_id'] - 1
                    if(value < 0):
                        value = 0
                    else:
                        value = value
                    print(value)
                    fix_value = int((125 * value) - 500)
                    print(fix_value)
                    # digital to analog 4~20
                    if(fix_value > 4000 and fix_value < 20000):
                        write = client.write_register(sensor_id, fix_value, unit=2)
                        print(write)
                    elif(fix_value < 4000):
                        write = client.write_register(sensor_id, 4000, unit=2)
                    elif(fix_value > 20000):
                        write = client.write_register(sensor_id, 20000, unit=2)
        except Exception as e:
            do_nothing = ''
    else:
        do_nothing = ''
    time.sleep(2)
