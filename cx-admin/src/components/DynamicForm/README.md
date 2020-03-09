General purpose form creator for Vuetify.

# Supports
- string
- number
- textarea
- boolean
- image
- json (TODO)
- enum
- date
- datetime
- relation


# Usage
```html
 <DynamicForm
    v-model="form"
    :fields="_fields"
    :primaryKey="_id"
/>
```