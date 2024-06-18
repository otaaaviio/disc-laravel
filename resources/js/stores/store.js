import { createStore } from 'vuex';
import { auth } from './auth.js'; // Certifique-se de que o caminho do arquivo está correto

export default createStore({
    modules: {
        auth
    }
});
