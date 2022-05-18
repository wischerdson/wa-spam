let client = null
export const setClient = ($axios) => client = $axios


export const getAccounts = () => client.get('/accounts')
export const addAccount = data => client.post('/new-account', data)
export const getMessage = () => client.get('/bot-reply-message')
export const updateMessage = data => client.post('/update-reply-message', data)
export const uploadFile = data => client.post('/upload-message-file', data, {
    headers: { 'Content-Type': 'multipart/form-data' }
})
