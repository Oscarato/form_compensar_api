define({ "api": [
  {
    "type": "get",
    "url": "/employees",
    "title": "Peticion lista de empleados",
    "name": "Employees",
    "group": "Employess",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "limit",
            "description": "<p>Limite de lista</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "offset",
            "description": "<p>Posicion de inicio de datos para lista</p>"
          },
          {
            "group": "Parameter",
            "type": "JSON",
            "optional": false,
            "field": "filter",
            "description": "<p>json de filtro</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "JSON",
            "optional": false,
            "field": "json",
            "description": "<p>con la lista de empleados</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Controllers/Employee.php",
    "groupTitle": "Employess"
  }
] });