export default [
  {
    name: 'permission',
    description: '',
    mainField: '',
    connection: 'default',
    collectionName: 'users-permissions_permission',
    attributes: [
      {
        name: 'type',
        params: {
          type: 'string',
          required: true,
          configurable: false
        }
      },
      {
        name: 'controller',
        params: {
          type: 'string',
          required: true,
          configurable: false
        }
      },
      {
        name: 'action',
        params: {
          type: 'string',
          required: true,
          configurable: false
        }
      },
      {
        name: 'enabled',
        params: {
          type: 'boolean',
          required: true,
          configurable: false
        }
      },
      {
        name: 'policy',
        params: {
          type: 'string',
          configurable: false
        }
      },
      {
        name: 'role',
        params: {
          plugin: 'users-permissions',
          configurable: false,
          target: 'role',
          key: 'permissions',
          nature: 'manyToOne',
          targetColumnName: ''
        }
      }
    ]
  },
  {
    name: 'role',
    description: '',
    mainField: '',
    connection: 'default',
    collectionName: 'users-permissions_role',
    attributes: [
      {
        name: 'name',
        params: {
          type: 'string',
          minLength: 3,
          required: true,
          configurable: false
        }
      },
      {
        name: 'description',
        params: {
          type: 'string',
          configurable: false
        }
      },
      {
        name: 'type',
        params: {
          type: 'string',
          unique: true,
          configurable: false
        }
      },
      {
        name: 'permissions',
        params: {
          plugin: 'users-permissions',
          configurable: false,
          isVirtual: true,
          target: 'permission',
          key: 'role',
          nature: 'oneToMany',
          targetColumnName: ''
        }
      },
      {
        name: 'users',
        params: {
          configurable: false,
          plugin: 'users-permissions',
          isVirtual: true,
          target: 'user',
          key: 'role',
          nature: 'oneToMany',
          targetColumnName: ''
        }
      }
    ]
  },
  {
    name: 'user',
    description: '',
    mainField: '',
    connection: 'default',
    collectionName: 'users-permissions_user',
    attributes: [
      {
        name: 'username',
        params: {
          type: 'string',
          minLength: 3,
          unique: true,
          configurable: false,
          required: true
        }
      },
      {
        name: 'email',
        params: {
          type: 'email',
          minLength: 6,
          configurable: false,
          required: true
        }
      },
      {
        name: 'provider',
        params: {
          type: 'string',
          configurable: false
        }
      },
      {
        name: 'password',
        params: {
          type: 'password',
          minLength: 6,
          configurable: false,
          private: true
        }
      },
      {
        name: 'resetPasswordToken',
        params: {
          type: 'string',
          configurable: false,
          private: true
        }
      },
      {
        name: 'confirmed',
        params: {
          type: 'boolean',
          default: false,
          configurable: false
        }
      },
      {
        name: 'blocked',
        params: {
          type: 'boolean',
          default: false,
          configurable: false
        }
      },
      {
        name: 'role',
        params: {
          plugin: 'users-permissions',
          configurable: false,
          target: 'role',
          key: 'users',
          nature: 'manyToOne',
          targetColumnName: ''
        }
      }
    ]
  }
]
