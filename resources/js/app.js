import './bootstrap';
import { createApp } from 'vue';

// Импортируем компоненты
import ExampleComponent from './components/ExampleComponent.vue';
import ExcelUpload from './components/ExcelUpload.vue';

// Создаём приложение Vue
const app = createApp({});

// Регистрируем компоненты
app.component('example-component', ExampleComponent);
app.component('excel-upload', ExcelUpload);

// Монтируем приложение
app.mount('#app');
