<template>
    <div>
        <li class="list-group-item" :class="className"><slot></slot></li>
        <small class="badge float-right" :class="badgeClass">{{ userName }}</small>
    </div>
</template>
<script>
export default {
    props: {
        colorClass: {
            type: String,
            required: false,
            default: 'warning'
        },
        userName: {
            type: String,
            required: true,
        },
    },
    computed: {
        className() {
            return 'list-group-item-' + this.colorClass;
        },
        badgeClass() {
            return 'badge-' + this.colorClass;
        }
    }
}
</script>
