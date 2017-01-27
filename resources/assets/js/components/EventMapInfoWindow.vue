<template>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ event.place.name }}
            </p>
            <a class="card-header-icon is-pulled-right" v-if="hasMultipleEvents">
              <span @click="triggerNextEvent">Next Event &gt;</span>
            </a>
        </header>
        <div class="card-image">
            <figure class="image is-16by9">
                <img :src="event.extra_info.cover.source" alt="Image">
            </figure>
        </div>
        <div class="card-content">
            <div class="content">
                <h3>
                    <a :href="facebookEventUrl(event.facebook_id)">{{ event.name }}</a>
                </h3>
                <br>
                <small>{{ event.start_time }} - {{ event.end_time }}</small>
            </div>
        </div>
    </div>
</template>

<script>
    import clone from 'lodash/clone'
    import {MapElementMixin} from 'vue2-google-maps';

    const props = {
        options: {
            type: Object,
            required: false,
            default () {
                return {};
            }
        },
        content: {
            default: null
        },
        opened: {
            type: Boolean,
            default: false,
        },
        event: {
            type: Object
        },
        hasMultipleEvents: {
            type: Boolean,
            default: false
        }
    };

    export default {
        mixins: [MapElementMixin],
        props: props,
        data() {
            return {

            }
        },
        deferredReady() {
            this.$markerObject = null;
            this.$markerComponent = this.$findAncestor(
                (ans) => ans.$markerObject
            );

            if (this.$markerComponent) {
                this.$markerObject = this.$markerComponent.$markerObject;
            }
            this.createInfoWindow(this.$map);
        },
        methods: {
            facebookEventUrl: function (id) {
                return 'https://facebook.com/events/' + id;
            },
            openInfoWindow () {
                if(this.opened) {
                    if (this.$markerObject !== null) {
                        this.$infoWindow.open(this.$map, this.$markerObject);
                    } else {
                        this.$infoWindow.open(this.$map);
                    }
                } else {
                    this.$infoWindow.close();
                }
            },
            destroyed () {
                if (this.disconnect) {
                    this.disconnect();
                }
                if (this.$infoWindow) {
                    this.$infoWindow.setMap(null);
                }
            },
            createInfoWindow(map) {
                // setting options
                const options = clone(this.options);
                options.content = this.$el;

                this.$infoWindow = new google.maps.InfoWindow(options);

                /**
                 * @see http://en.marnoto.com/2014/09/5-formas-de-personalizar-infowindow.html
                 */
                google.maps.event.addListener(this.$infoWindow, 'domready', function() {
                    let iwOuter = this.content.parentElement.parentElement,
                        iwBackground = iwOuter.previousElementSibling;

                    iwBackground.children[1].style.display = 'none';
                    iwBackground.children[2].style.zIndex = 1;
                    iwBackground.children[3].style.display = 'none';
                });

                this.openInfoWindow();
                this.$watch('opened', () => {
                    this.openInfoWindow();
                });
            },
            triggerNextEvent: function () {
                this.$emit('nextEventClicked');
            }
        }
    }
</script>

<style lang="scss">
    .gm-style-iw {
        width: 350px !important;
        top: 15px !important;
        background-color: #fff;
        border-radius: 2px 2px 10px 10px;
        overflow: visible !important;
        > div {
            display: block !important;
              overflow: visible !important;
        }
    }
</style>