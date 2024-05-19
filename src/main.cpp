#include "ArduinoJson.h"
#include "DHT.h"
#include <WiFi.h>
#include "PubSubClient.h"

#define DHTPIN 2
#define DHTTYPE DHT22

const char * MQTTServer = "broker.emqx.io";
const char * MQTT_Topic = "Buoi4";
// Tạo ID ngẫu nhiên tại: https://www.guidgen.com/
const char * MQTT_ID = "d9909e81-71ec-408f-9edd-de2a55df5c3c";
int Port = 1883;
const int ledPin = 2;

WiFiClient espClient;
PubSubClient client(espClient);
DHT dht(DHTPIN, DHTTYPE);

void WIFIConnect() {
  Serial.println("Connecting to SSID: Wokwi-GUEST");
  WiFi.begin("Wokwi-GUEST", "");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("WiFi connected");
  Serial.print(", IP address: ");
  Serial.println(WiFi.localIP());
}

void MQTT_Reconnect() {
  while (!client.connected()) {
    if (client.connect(MQTT_ID)) {
      Serial.print("MQTT Topic: ");
      Serial.print(MQTT_Topic);
      Serial.print(" connected");
      client.subscribe(MQTT_Topic);
      Serial.println("");
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");
      delay(5000);
    }
  }
}

void callback(char* topic, byte* message, unsigned int length) {
  Serial.print("Message arrived on topic: ");
  Serial.println(topic);
  String stMessage;

  float h = dht.readHumidity();
  if (h > 85.0) {
    client.publish("humidity_alert", "High humidity detected!");
  }
}

void setup() {
  Serial.begin(115200);
  WIFIConnect();
  client.setServer(MQTTServer, Port);
  client.setCallback(callback);
  dht.begin();
}


void loop() {
  delay(100);
  if (!client.connected()) {
    MQTT_Reconnect();
  }
  client.loop();
  float h = dht.readHumidity();
  float t = dht.readTemperature();

  if (isnan(h) || isnan(t)) {
    Serial.println("Failed to read from DHT sensor!");
    return;
  }
  // thasi dusi
  StaticJsonDocument<100> doc;
  doc["temperature"] = t;
  doc["humidity"] = h;
  char payload[100];
  serializeJson(doc, payload);
  client.publish(MQTT_Topic, payload);
}

/*
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js"
        type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" type="text/javascript"></script>
  </head>

  <body>
    <div class="nhietdo">
        <label for="">Nhiệt độ</label>
        <p class="value__NhietDo">0</p>
    </div>
    <div class="doam">
        <label for="">Độ Ẩm</label>
        <p class="value__DoAm">0</p>
    </div>
    <div class="time"></div>
  </body>

  </html>

  <script>
    const topic = "Buoi4";
    const client = new Paho.MQTT.Client("broker.emqx.io", Number(8083), "0000");

    client.onConnectionLost = onConnectionLost;
    client.onMessageArrived = onMessageArrived;

    client.connect({ onSuccess: onConnect });

    function onConnect() {
        console.log("onConnect");
        client.subscribe(topic);
    }

    function onConnectionLost(responseObject) {
        if (responseObject.errorCode !== 0) {
            console.log("onConnectionLost:" + responseObject.errorMessage);
        }
    }
    let temperatureSum = 0;
    let temperatureCount = 0;

    function onMessageArrived(message) {
        console.log("onMessageArrived:" + message.payloadString);
        const data = JSON.parse(message.payloadString);
        temperatureSum += data.temperature;
        temperatureCount++;
        if (temperatureCount === 3) {
            const averageTemperature = temperatureSum / 3;
            $(".value__NhietDo").text(averageTemperature.toFixed(2));
            temperatureSum = 0;
            temperatureCount = 0;
        }
        $(".value__DoAm").text(data.humidity);
        if (data.humidity > 85) {
            console.log("Độ ẩm cao")
        }
        let now = new Date();

        $(".time").text("Thời Gian: " + `${now.getDate()}/${now.getMonth()+1}/${now.getFullYear()}`);
    }
  </script>
*/