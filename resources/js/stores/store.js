import {createStore} from 'vuex';
import {auth} from './auth.js';
import {guilds} from "./guilds.js";
import {channel} from "./channel.js";
import {message} from "./message.js";

export default createStore({
    modules: {
        auth,
        guilds,
        channel,
        message
    }
});
