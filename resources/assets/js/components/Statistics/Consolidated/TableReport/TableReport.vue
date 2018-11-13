<template>
    <div>
        <table class="table table-sm table-bordered">
            <thead>
            <tr style="font-size: 11px; background-color: #1d75b3; color: #FFFFFF">
                <th scope="col">No.</th>
                <th>NOMBRES Y APELLIDOS</th>
                <th>TAV</th>
                <th>PUESTO</th>
                <th>PGG</th>
                <th v-for="asignature in propData.asignatures">
                    <div data-toggle="tooltip" data-placement="top" :title="asignature.name">
                        {{asignature.abbreviation}}
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            <template v-for="(enrollment,i) in propData.enrollments">
                <!-- Final -->
                <tr>
                    <td style="text-align: left !important;" rowspan="2">{{i+1}}</td>
                    <td style="text-align: left !important;" rowspan="2">
                        {{fullname(enrollment)}}
                    </td>
                    <td rowspan="2">{{enrollment.tav}}</td>
                    <td rowspan="2">{{enrollment.rating}}</td>
                    <td rowspan="2">{{enrollment.average.toFixed(1)}}</td>
                    <td v-for="asignature in propData.asignatures">
                        <span v-html="getAccumulated(enrollment,asignature)"></span>
                    </td>
                </tr>
                <tr style="background-color: rgb(255, 253, 236); border-bottom: 1px solid #1d75b3 !important;">
                    <td v-for="asignature in propData.asignatures">
                        <span v-html="getReport(enrollment,asignature)"></span>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>

    </div>
</template>

<script>
    export default {
        name: "table-report",
        props: ["prop-data"],
        methods: {
            fullname(enrollment) {
                return enrollment.student_last_name + " " + enrollment.student_name
            },
            getAccumulated(enrollment, asignature) {
                let average = 0;
                enrollment.accumulatedSubjects.forEach(subjects => {
                    if (subjects.asignatures_id == asignature.asignatures_id) {
                        average = subjects.average.toFixed(1);
                    }
                })
                return average != 0 ? average : '';
            },
            getReport(enrollment, asignature) {
                let report = "";
                enrollment.finalReport.forEach(subjects => {
                    if (subjects.asignatures_id == asignature.asignatures_id) {
                        report = subjects.report;
                        if (subjects.report == "REP")
                            report = '<span style="color:red;">' + subjects.report + '</span>'
                    }
                })

                return report;
            },

        }
    }
</script>

<style scoped>

</style>