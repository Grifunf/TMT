# TMT
Terraforming Mars Tracker

## **Installing**

```bash
git clone https://github.com/Grifunf/TMT.git
docker build -t tmt:0.2.x .
docker run -p 80:80 -p 6001:6001 --name=tmt -dit tmt:0.2.x
```