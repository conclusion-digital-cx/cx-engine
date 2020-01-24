module.exports = {
  "env": {
    "amd": true
  },
  "extends": [
    "plugin:vue/recommended",
    "standard"
  ],
  plugins: [
    'vuetify'
  ],
  "rules": {
    "vue/max-attributes-per-line": [
      "error",
      {
        "singleline": 4,
        "multiline": {
          "max": 4,
          "allowFirstLine": false
        }
      }
    ],
  }
}