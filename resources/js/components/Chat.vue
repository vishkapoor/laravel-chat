<template>
<div class="col-4 offset-4 offset-sm-1 col-sm-10">
    <li class="list-group-item active">
        Chat Room
        <span class="float-right">
            Total Users: {{ numberOfUsers }}
        </span>
    </li>
    <ul class="list-group" v-chat-scroll>
        <message
            :key="value.index"
            v-for="(value, index) in chat.messages"
            :user-name=chat.users[index]
            :color-class=chat.colors[index]
            :time=chat.times[index]>
            {{ value }}
        </message>
    </ul>
    <div
        v-if="typing.length"
        class="badge badge-primary badge-pill">
        {{ typing }}
    </div>
    <input
        @keyup.enter="send"
        placeholder="Type your message here"
        v-model="message"
        type="text"
        style="height:45px"
        class="form-control">
    <br/>
    <a
        :class=" !hasMessages ? 'disabled' : '' "
        href=""
        @click.prevent="clearChat"
        class="btn btn-sm btn-danger">Clear Chat</a>
</div>
</template>
<script type="text/javascript">
export default {
    name: "Chat",
    mounted() {
        Echo.private('chat')
            .listen('ChatEvent', (e) => {
                this.chat.messages.push(e.message);
                this.chat.users.push(e.userName);
                this.chat.colors.push('warning');
                this.chat.times.push(this.getTime());
                this.saveChat();
            })
            .listenForWhisper('typing', (e) => {
                this.typing = '';
                if(!_.isEmpty(e.name)) {
                    this.typing = 'typing...';
                }
            });

        Echo.join('chat')
            .here((users) => {
               this.numberOfUsers = users.length;
            })
            .joining((user) => {
              this.numberOfUsers += 1;
              this.$toaster.success(user.name + ' is joined the chat room.');
            })
            .leaving((user) => {
               this.numberOfUsers -= 1;
               this.$toaster.warning(user.name + ' has left the chat room.');
            });

        this.getChat();
    },
    data() {
        return {
            message: '',
            chat: {
                messages: [],
                users: [],
                colors: [],
                times:[]
            },
            typing: '',
            numberOfUsers: 0,
        }
    },
    computed: {
        hasMessages() {
            return this.chat.messages.length > 0;
        }
    },
    methods: {
        clearChat() {
            if(!this.chat.messages.length) {
                return;
            }
            axios.post('/clear-chat')
                .then(response => {
                    this.chat.messages = [];
                    this.chat.users = [];
                    this.chat.colors = [];
                    this.chat.times = [];
                    this.$toaster.success('Chat has been cleared successfully.');
                });
        },
        clearMessage() {
            this.message = '';
        },
        saveChat() {
            axios.post('/save-chat', {
                chat: this.chat,
            })
            .then(response => console.log(response.data))
            .catch(error => console.log(error));
        },
        send() {
            if(!this.message.length) {
                return;
            }

            this.chat.messages.push(this.message);
            this.chat.users.push('You');
            this.chat.colors.push('success');
            this.chat.times.push(this.getTime());

            axios.post('/chat', {
                message: this.message,
                chat: this.chat
            })
            .then((response) => {
                console.log(response)
                this.clearMessage();
            })
            .catch((e) => console.log(e));
        },
        getTime() {
            let time = new Date();
            return time.getHours() + ':' + time.getMinutes();
        },
        getChat() {
            axios.get('/chat')
                .then(response => {
                    if(!_.isEmpty(response.data)) {
                        this.chat = response.data;
                    }
                })
                .catch(error => console.log(error));
        }
    },
    watch: {
        message() {
            Echo.private('chat')
                .whisper('typing', {
                    name: this.message
                });
        }
    }
}
</script>
<style>
.list-group {
    overflow-y: scroll;
    min-height: 200px;
    max-height: 200px;
    border: 1px solid #CED4DA;
    border-radius:5px;
}
</style>