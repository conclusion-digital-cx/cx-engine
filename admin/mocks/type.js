{
    "_id": 1,
    "name": "permission",
    "description": "",
    "mainField": "",
    "connection": "default",
    "collectionName": "users-permissions_permission",
    "attributes": [
      {
        "name": "type",
        "params": {
          "type": "string",
          "required": true,
          "configurable": false
        }
      },
      {
        "name": "controller",
        "params": {
          "type": "string",
          "required": true,
          "configurable": false
        }
      },
      {
        "name": "action",
        "params": {
          "type": "string",
          "required": true,
          "configurable": false
        }
      },
      {
        "name": "enabled",
        "params": {
          "type": "boolean",
          "required": true,
          "configurable": false
        }
      },
      {
        "name": "policy",
        "params": {
          "type": "string",
          "configurable": false
        }
      },
      {
        "name": "role",
        "params": {
          "plugin": "users-permissions",
          "configurable": false,
          "target": "role",
          "key": "permissions",
          "nature": "manyToOne",
          "targetColumnName": ""
        }
      }
    ]
  },