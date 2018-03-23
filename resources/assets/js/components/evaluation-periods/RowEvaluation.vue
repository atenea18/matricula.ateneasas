<template>
    <tr>
        <td>{{setting.index+1}}</td>
        <td style="width:320px"> {{fullName}}</td>
        <td> 2</td>
        <template v-for="parameter in parameters">
            <td v-for="note_parameter in parameter.notes_parameter">
                <input-evaluation
                        :ref="''+setting.enrollment.id + asignature.id + periodSelected + parameter.id"
                        :setting="setting" :noteparameter="note_parameter"
                        :parameter="parameter"></input-evaluation>
            </td>
            <input-parameter :ref="refsInputParameter" :setting="setting" :parameter="parameter"/>
        </template>
        <td style="padding-top:16px;width:15px">
		 <label >{{valuenote.toFixed(2)}} </label>
        </td>
    </tr>
</template>

<script>
import { mapState, mapMutations, mapGetters } from "vuex";
import InputEvaluation from "./InputEvaluation";
import InputParameter from "./InputParameter";

export default {
  name: "row-evaluation",
  components: { InputEvaluation, InputParameter },
  data() {
    return {
      enrollmentid: 0,
      isExistEvaluationPeriod: false,
      evaluationperiodid: 0,
      valuenote: 0,
      state: false,
      noteend: 0
    };
  },
  created() {
    this.enrollmentid = this.setting.enrollment.id;
    this.getInputsParameters();
    this.eventForUpdateInputParameter("set-dirty-initial", "set-refs");
    this.eventForUpdateInputParameter("set-dirty", "set-refs");

    //this.eventForUpdateInputParameter("set-dirty-input","set-refs-input");
  },
  computed: {
    ...mapState(["parameters", "asignature", "periodSelected"]),

    fullName() {
      return (
        this.setting.enrollment.student_last_name +
        " " +
        this.setting.enrollment.student_name
      );
    },
    refsInputParameter() {
      return ""+this.setting.enrollment.id + this.$store.state.asignature.id + this.$store.state.periodSelected
    },
    parametersAll() {
      return this.$store.state.parameters;
    }
  },
  props: {
    setting: { type: Object }
  },
  methods: {
    eventForUpdateInputParameter(keyEvent, KeyEmit) {
      this.parameters.forEach(parameter => {
        let nameEvent =
          "" +
          this.setting.enrollment.id +
          this.$store.state.asignature.id +
          this.$store.state.periodSelected +
          parameter.id;

        this.$bus.$off(keyEvent + "-" + nameEvent);
        this.$bus.$on(keyEvent + "-" + nameEvent, pthis => {
          let arraychilds = this.$refs[pthis];
          this.$bus.$emit(KeyEmit + "-" + nameEvent, arraychilds);
        });
      });
    },

    getInputsParameters() {
      let nameRef =
        "" +
        this.setting.enrollment.id +
        this.$store.state.asignature.id +
        this.$store.state.periodSelected;

      this.$bus.$off("set-note-" + nameRef);
      this.$bus.$on("set-note-" + nameRef, keyName => {
        let arraychilds = this.$refs[keyName];
        if (typeof arraychilds == "object") {
          this.valuenote = 0;
          arraychilds.forEach(e => {
            this.valuenote += e.value;
          });
        }
      });
    }
  }
};
</script>

<style scoped>

</style>