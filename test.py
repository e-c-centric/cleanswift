import requests

url = "https://ghanapostgps.sperixlabs.org/get-location"

payload = 'address=AK-484-9321'
headers = {
  'Content-Type': 'application/x-www-form-urlencoded'
}

response = requests.request("POST", url, headers=headers, data = payload)

print(response.json())