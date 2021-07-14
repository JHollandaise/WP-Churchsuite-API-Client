A WordPress plug-in enabling structured communication with the [ChurchSuite-API](https://github.com/ChurchSuite/churchsuite-api) 

# API Server Access
In order to access your own ChurchSuite server, you must populate the `x_header.json` file in the top level directory with the relevant API access header variables. For example:

#### **`x_headers.json`**
```json
{
    "X_Account": "livingchurch",
    "X-Application": "attendance-dashboard",
    "X-Auth": "SECRET_TOKEN"
}
```
NOTE: an example file is included called `x_headers.json.example` just rename and populate this file: `mv x_headers.json.example x_headers.json`
 
