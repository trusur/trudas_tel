from datetime import datetime
import subprocess
import mysql.connector
import time
import pathlib
import psycopg2

try:
    mydb = psycopg2.connect(
        host="localhost", database="trudas_db", user="postgres", password="root", )
    mycursor = mydb.cursor()
    print("[V] DB CONNECTED")
except Exception as e:
    print("[X]  DB Not Connected " + e)

while True:

    now = datetime.now()

    # check backup day
    sql_backup_day = (
        "SELECT day_backup FROM configurations")
    mycursor.execute(sql_backup_day)
    day_backup = mycursor.fetchone()[0]

    if(now.strftime("%Y%m%d %H:%M:%S") == now.strftime("%Y%m" + str(day_backup) + " 00:15:00")):

        dt_string = now.strftime("%Y%m%d_%H%M%S")

        mysqldumppatch = "C:/PostgreSQL/14/bin/"
        subprocess.Popen(str(mysqldumppatch) + "pg_dump -w -h localhost -U postgres -p 5432 trudas_db > backup/trudas_db_backup_" +
                         dt_string + ".pgsql", shell=True)
        print('auto backup success')
        time.sleep(3600)
