<template>
    <div>
        hola
    </div>
</template>

<script>
    export default {
        name: "stats-rating",
        created(){
          this.managerEvents()
        },
        methods:{
            managerEvents() {
                this.$bus.$on('spire', object => {
                    if(object.type == 'stats-rating'){
                       console.log("Hola")
                    }
                })
            },
        }
    }
</script>

<style scoped>

</style>