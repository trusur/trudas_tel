import subprocess
import sys
import time

subprocess.Popen("php spark serve", shell=True)
time.sleep(1)
subprocess.Popen("php get.php", shell=True)
time.sleep(1)
subprocess.Popen("python auto_backup.py", shell=True)
time.sleep(1)
subprocess.Popen("php spark command:averaging", shell=True)
print("php gui\spark command:averaging")
time.sleep(1)
# subprocess.Popen("php spark command:sendingdata", shell=True)
# print("php gui\spark command:sendingdata")
time.sleep(1)
subprocess.Popen("gui.bat", shell=True)
