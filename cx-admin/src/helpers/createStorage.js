// localStorage persistence
export const createStorage = (STORAGE_KEY = 'columns-mynutriopt') => {
  return {
    fetch () {
      return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]')
    },
    save (todos) {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(todos))
    }
  }
}
