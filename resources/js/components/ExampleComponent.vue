<template>
    <div class="card">
        <div class="card-header">
            <h3>Пример Vue компонента</h3>
        </div>
        <div class="card-body">
            <p>Вы видите это сообщение? Vue.js работает! 🎉</p>
            <button @click="counter++" class="btn btn-primary">
                Кликнуто раз: {{ counter }}
            </button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            counter: 0
        }
    }
}
</script>

<style scoped>
.card {
    margin: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.card-header {
    margin-bottom: 15px;
}
.btn {
    padding: 10px 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn:hover {
    background: #0056b3;
}
</style>
