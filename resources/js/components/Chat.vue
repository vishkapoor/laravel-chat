<template>
<div class="col-4 offset-4 offset-sm-1 col-sm-10">
    <li class="list-group-item active">Chat Room</li>
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
            })
            .listenForWhisper('typing', (e) => {
                this.typing = '';
                if(!_.isEmpty(e.name)) {
                    this.typing = 'typing...';
                }
            });
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
        }
    },
    methods: {
        clearMessage() {
            this.message = '';
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