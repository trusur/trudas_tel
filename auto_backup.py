from datetime import datetime
import subprocess
# import mysql.connector
import time
import pathlib
import psycopg2

try:
    mydb = psycopg2.connect(
        host="localhost", database="trudas_db", user="postgres", password="root", )
    mycursor = mydb.cursor()
    # print("[V] DB CONNECTED")
except Exception as e:
    do_nothing = ''
    # print("[X]  DB Not Connected " + e)

while True:

    now = datetime.now()

    # check backup day
    sql_backup_day = (
        "SELECT day_backup FROM configurations")
    mycursor.execute(sql_backup_day)
    day_backup = mycursor.fetchone()[0]

    if(now.strftime("%Y%m%d %H:%M") == now.strftime("%Y%m" + str(day_backup) + " 15:50")):
        time_backup = now.strftime("%Y%m%d_%H%M")
        subprocess.Popen(
            "pg_dump.exe --dbname=postgresql://postgres:root@localhost:5432/trudas_db > backup/trudas_db_backup_"+str(time_backup)+".sql", shell=True)
        time.sleep(1)
