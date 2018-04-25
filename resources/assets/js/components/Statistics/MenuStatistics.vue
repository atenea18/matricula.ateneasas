<template>
    <div>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                    <ul class="nav navbar-nav">
                        <li :class="currentView=='stats-consolidated'?'active':''">
                            <a href="#consolidated" @click="setCurrentView('stats-consolidated')">Consolidados
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                    </ul>

                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </div>
</template>

<script>

    import {mapState} from 'vuex'

    export default {
        name: "menu-statistics",
        components: {},
        data() {
            return {}
        },
        computed: {
            ...mapState([
                'currentView',
            ]),

        },
        methods: {
            setCurrentView(view) {
                this.$store.state.currentView = view
            },

        },
    }
</script>

<style>

</style>