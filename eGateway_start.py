import subprocess
import sys
import time

subprocess.Popen("php spark serve --host 10.38.101.61", shell=True)
time.sleep(1)
subprocess.Popen("python adam_reader.py", shell=True)
time.sleep(1)
subprocess.Popen("python weather_reader.py", shell=True)
time.sleep(1)
subprocess.Popen("python auto_backup.py", shell=True)
time.sleep(1)
subprocess.Popen("php spark command:averaging", shell=True)
print("php gui\spark command:averaging")
time.sleep(1)
subprocess.Popen("php spark command:sendingdata", shell=True)
print("php gui\spark command:sendingdata")
time.sleep(1)
subprocess.Popen("php spark command:backup_table", shell=True)
print("php gui\spark command:backup_table")
time.sleep(1)
subprocess.Popen("gui.bat", shell=True)
