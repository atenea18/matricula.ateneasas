<template>
    <tr>
        <td>{{objectInput.index+1}}</td>
        <td> {{ fullname }}</td>
        <td>{{(getTav()==0)?'':getTav()}}</td>
        <td v-for="asignature in objectInput.asignatures">
            {{ getValueFinal(asignature)}}
        </td>
    </tr>
</template>

<script>
    export default {
        name: "row-table-consolidated",
        props: {
            objectInput: {type: Object}
        },
        data() {
            return {
                tav:0
            }
        },
        created(){

        },
        computed: {
            fullname() {
                return this.objectInput.enrollment.student_last_name + " " + this.objectInput.enrollment.student_name
            }
        },
        methods:{
            getValueFinal(asignature){

                let value = ""
                this.objectInput.enrollment.notes_final.forEach( (element, i) => {
                    if(element.asignatures_id == asignature.asignatures_id && element.value>0){
                        value = element.value.toFixed(2)
                    }
                })

                return value
            },

            getTav(){
                let count = 0
                this.objectInput.enrollment.notes_final.forEach( (element, i) => {
                    if(element.value!=0 && element.value != ""){
                        //console.log(element.value)
                        count++
                    }
                })
                count = count==0?"":count;
                return count
            }

        }
    }
</script>

<style scoped>

</style>