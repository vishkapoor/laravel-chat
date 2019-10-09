<template>
<div class="col-4 offset-4">
    <li class="list-group-item active">Chat Room</li>
    <ul class="list-group" v-chat-scroll>
        <message
            :key="value.index"
            v-for="value in chat.messages"
            color-class="success">
            {{ value }}
        </message>
    </ul>
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

    data() {
        return {
            message: '',
            chat: {
                messages: []
            }
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
            this.clearMessage();
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