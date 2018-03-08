<template>
    <div class="form-group padding-at">
        <label>Seleccionar {{type.name}}</label>
        <select v-on:change="selected" class="form-control" name="" v-model="id">
            <option :value="0">Seleccionar</option>
            <option v-for="row in data" :value="row.id">
                {{ row.name }}
            </option>
        </select>
        <ul v-show="picked" class="content-error">
            <li v-for="error in errors">
                {{error.message}}
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "at-select",
        props: {
            data: {type: Array},
            type: {type: Object}
        },
        data() {
            return {
                id: this.type.id || 0,
                picked: false,
                errors: []
            }
        },
        methods: {
            selected() {
                //Dispara evento
                if (this.type.validate == true) {
                    if (this.id == 0) {
                        this.errors.push({message: "campo " + this.type.name + " debe ser seleccionado"})
                        this.picked = true;
                    }else{
                        this.errors = []
                    }
                }
                this.$bus.$emit('selected-id-' + this.type.nameEv, this.id)
                console.log("Selected " + this.type.nameEv + " id: " + this.id)
            },
        },
        created() {
            this.$bus.$on('selected-id-' + this.type.tby, (id) => {
                this.id = 0;
            });

        }
    }
</script>

<style>
    .content-error {
        padding-top: 4px;
        padding-left: 1px;
    }

    .content-error li {
        text-decoration: none;
        font-size: 12px;
        color: red;
        list-style: none;
        text-transform: lowercase;
    }
</style>