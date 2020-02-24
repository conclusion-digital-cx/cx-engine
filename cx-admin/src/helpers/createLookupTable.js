// Helper to create a lookup table of an Array
export const createLookupTable = (arr, key = 'value') => {
  const lookupTable = {}
  arr.forEach(elem => {
    const uid = elem[key]
    lookupTable[uid] = elem
  })
  return lookupTable
}
