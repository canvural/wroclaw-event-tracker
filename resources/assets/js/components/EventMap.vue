<template>
    <div id="event-map">
        <gmap-map
            :center="{lat:51.107885, lng:17.038538}"
            map-type-id="terrain"
            :zoom="15"
            style="height: 500px"
        >
            <gmap-marker
                v-for="m in markers"
                :key="m[0].id"
                :position=makeMarkerLocation(m[0])
                :clickable="true"
                :animation=2
                @click="markerClicked(m)"
            >
                <map-event-info-window
                    v-for="event in m"
                    :key="m[0].id"
                    :opened="event.id == currentOpenEventId"
                    :event=event
                    :hasMultipleEvents="m.length > 1"
                    v-on:nextEventClicked="nextEventClicked(m)"
                >

                </map-event-info-window>
            </gmap-marker>
        </gmap-map>
        <div id="event-search" class="level">
            <div class="level-item">
                <input type="text" class="input" v-model="searchQuery" placeholder="Search for event">
            </div>
            <div class="level-item">
                <datepicker></datepicker>
            </div>
        </div>
    </div>
</template>

<script>
    import debounce from 'lodash/debounce'
    import Datepicker from 'vuejs-datepicker'
    import EventInfoWindow from './EventMapInfoWindow.vue'

    export default {
        components: {
            'map-event-info-window': EventInfoWindow,
            'datepicker': Datepicker
        },
        data() {
          return {
              markers: [],
              searchQuery: '',
              currentOpenEventId: 0
          };
        },
        mounted() {
            axios.get('/api/events')
                .then(({data}) => {
                    this.markers = data
                });
        },
        watch: {
            searchQuery: debounce(function () {
                axios.get('/api/events?q=' + this.searchQuery)
                    .then(({data}) => {
                        this.markers = data;
                        this.currentOpenEventId = 0;
                    });
            }, 500)
        },
        methods: {
            markerClicked: function (m) {
                if((this.currentOpenEventId != 0 && m.length > 1) || this.currentOpenEventId == m[0].id) {
                    this.currentOpenEventId = 0;
                } else {
                    this.currentOpenEventId = m[0].id;
                }
            },
            nextEventClicked: function (m) {
                let nextEvent = m.find((x) => x.id != this.currentOpenEventId);
                this.currentOpenEventId = nextEvent.id;
            },
            makeMarkerLocation: function (m) {
                return {
                    lat: parseFloat(m.place.location.latitude),
                    lng: parseFloat(m.place.location.longitude)
                }
            }
        }
    }
</script>

<style lang="scss">
    #event-map {
        height: 100%;
    }

    #event-search {
        position: absolute;
        top: 60px;
        right: 10px;
    }
</style>
