
export default {
  title: { type: 'String' },
  description: { type: 'String' },
  start: { type: 'Date' },
  end: { type: 'Date' },
  //= > Put inside bookings table for easier queries
  // booking: { type: ObjectId, ref: 'bookings' },
  price: { type: 'Number' }, // Set custom price during this period
  suffix: { type: 'String' },
  pricenote: { type: 'String' },
  availability: { type: 'Boolean', default: false },
  // state: { type: "String", enum: states, default: 'not_available' },
  resource: { type: 'ObjectId', ref: 'resources' },
  resources: [{ type: 'ObjectId', ref: 'resources' }],
  orders: [{ type: 'ObjectId', ref: 'orders' }],
  location: { type: 'ObjectId', ref: 'locations' },

  private: { type: 'Boolean', default: false },
  // Restrictions
  // min: { type: "Number" }, // Set custom price during this period
  stock: { type: 'Number' }, // Set custom price during this period
  takenSpots: { type: 'Number' } // Set custom price during this period
}
