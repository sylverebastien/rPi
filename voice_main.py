import snowboydecoder
import sys
import signal
import os
import subprocess

interrupted = False

def detect():
	os.system("curl --data-urlencode \"text=Dis Je suis la\" \"http://localhost/domotique/index.php?q=ajax&action=glad\"")
	detector.terminate()
	subprocess.Popen(["python", "commands.py", "resources/radio.pmdl", "resources/meteo.pmdl", "resources/terminer.pmdl"])
	sys.exit(-1)

def end():
	os.system("curl --data-urlencode \"text=Dis au revoir\" \"http://localhost/domotique/index.php?q=ajax&action=glad\"")
	detector.terminate()
	sys.exit(-1)



def signal_handler(signal, frame):
    global interrupted
    interrupted = True


def interrupt_callback():
    global interrupted
    return interrupted

if len(sys.argv) == 1:
    print("Error: need to specify model name")
    print("Usage: python main.py your.model")
    sys.exit(-1)


models = sys.argv[1:]


# capture SIGINT signal, e.g., Ctrl+C
signal.signal(signal.SIGINT, signal_handler)

sensitivity = [0.5]*len(models)
detector = snowboydecoder.HotwordDetector(models, sensitivity=sensitivity)
callbacks = [lambda: detect(),
             lambda: end()]
             
print('Listening... Press Ctrl+C to exit')

# main loop
detector.start(detected_callback=callbacks,
               interrupt_check=interrupt_callback,
               sleep_time=0.03)

os.system("curl --data-urlencode \"text=Dis au revoir\" \"http://localhost/domotique/index.php?q=ajax&action=glad\"")
detector.terminate()
