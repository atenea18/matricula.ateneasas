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
                <th>ASIGNATURAS</th>
                <th>VAL. GUARDADA</th>
            </tr>
            </thead>
            <tbody>
            <template v-for="(enrollment,i) in propData.enrollments">
                <!-- Final -->
                <template v-if="eventFilter(enrollment)">
                    <tr v-for="(failedSubject, j) in enrollment.failedSubjects.subjects">
                        <template v-if="j==0">
                            <td style="text-align: left !important;" :rowspan="enrollment.failedSubjects.number">
                                {{i+1}}
                            </td>
                            <td style="text-align: left !important;" :rowspan="enrollment.failedSubjects.number">
                                {{fullname(enrollment)}}
                            </td>
                            <td :rowspan="enrollment.failedSubjects.number">
                                {{enrollment.failedSubjects.number}}
                            </td>
                            <td :rowspan="enrollment.failedSubjects.number">
                                {{enrollment.rating}}
                            </td>
                            <td :rowspan="enrollment.failedSubjects.number">
                                {{enrollment.accumulatedAverage.toFixed(1)}}
                            </td>
                        </template>
                        <td>{{failedSubject.name}}</td>
                        <td>
                            <span v-html="getValue(failedSubject.average, failedSubject.overcoming)"></span>
                        </td>
                    </tr>
                </template>
            </template>
            </tbody>
        </table>

    </div>
</template>

<script>
    export default {
        name: "table-filter-report",
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
            eventFilter(enrollment) {
                let visible = false
                let condition = this.propData.params.objectValuesManagerGroupSelect.condition
                let number = this.propData.params.objectValuesManagerGroupSelect.condition_number

                switch (condition) {
                    case "0":
                        if (enrollment.failedSubjects.number == number) {
                            visible = true
                        }
                        break;
                    case "1":
                        if (enrollment.failedSubjects.number >= number) {
                            visible = true
                        }
                        break;
                    default:
                        if (enrollment.failedSubjects.number <= number) {
                            visible = true
                        }
                }
                return visible
            },
            getValue(average, overcoming) {
                if (overcoming) {
                    return '<span>' + average + " / " + overcoming + '</span>'
                }
                else
                    return '<span>' + average + '</span>'
            }

        }
    }
</script>

<style scoped>

</style>