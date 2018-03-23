<template>
    <td style="padding-top:16px;width:15px">
	<label v-show="value">{{value.toFixed(2)}} </label>
    </td>
</template>

<script>
import { mapState, mapMutations, mapGetters } from "vuex";

export default {
  name: "input-parameter",
  props: {
    setting: { type: Object },
    parameter: { type: Object }
  },
  computed: {
    ...mapState(["asignature", "periodSelected"])
  },
  methods: {
    calculate(element) {
      //Si la nota no tiene porcentaje
      if (element.percent == 0) {
        if (element.value > 0) {
          this.sumaZero += element.value;
          this.countPercentZero++;
        }
      }

      //Si la nota si tiene porcentaje example proyecto 30%
      if (element.percent > 0) {
        this.promedioWith += element.value * element.percent;
        this.percentWith += element.percent;
      }
      //Se le asigna el porcentaje restante a las notas sin porcentajes
      this.percentZero = 1 - this.percentWith;
      if (this.countPercentZero != 0) {
        this.promedioZero =
          this.sumaZero / this.countPercentZero * this.percentZero;
      }
    },
    initial() {
      (this.countPercentZero = 0),
        (this.percentZero = 0),
        (this.promedioZero = 0),
        (this.sumaZero = 0),
        (this.percentWith = 0),
        (this.promedioWith = 0);
    }
  },
  data() {
    return {
      value: 0,
      countPercentZero: 0,
      percentZero: 0,
      promedioZero: 0,
      sumaZero: 0,
      percentWith: 0,
      promedioWith: 0
    };
  },
  created() {
    let nameEvent =
      "" +
      this.setting.enrollment.id +
      this.$store.state.asignature.id +
      this.$store.state.periodSelected +
      this.parameter.id;

    this.$bus.$off("set-refs-" + nameEvent);
    this.$bus.$on("set-refs-" + nameEvent, childs => {
      this.initial();
      childs.forEach(element => {
        let elementNotes = {
          percent: parseFloat(element._props.noteparameter.percent / 100),
          value: parseFloat(element.valuenote == "" ? 0 : element.valuenote)
        };

        this.calculate(elementNotes);
      });
      this.value = (this.promedioZero + this.promedioWith) * (this.parameter.percent/100);
      let nameInputEvent =
        "" +
        this.setting.enrollment.id +
        this.$store.state.asignature.id +
        this.$store.state.periodSelected;
      this.$bus.$emit("set-note-" + nameInputEvent,nameInputEvent);
    });

    this.$bus.$emit("set-dirty-initial-" + nameEvent, nameEvent);
  }
};
</script>

<style scoped>

</style>