<?php
$plugin_version = $GLOBALS['version'];
?>
<div id="admin-ui-option">
    <div class="container-fluid">
        <div class="row row-wookit">
            <div class="col-12">
                <div class="page-title">
                    <div class="woo-col-6">
                        <i>
                            <img src="<?php echo DOMKIT_IMAGES . '/svg/toolbox-icon.svg' ?>">
                        </i>
                        <span>
                        <?php echo __('Woocommerce toolkit', 'dominokit') ?>
                        </span>
                    </div>

                    <div class="woo-col-6 woo-plugin-version">
                        <span>
                            <?php echo sprintf(__('Current version: %s', 'dominokit'), $plugin_version); ?>
                        </span>
                    </div>

                </div>
            </div>
            <div class="col-3">
                <section class="side-box">
                    <nav class="categories">
                        <ul>
                            <li v-for="(option, index) in options">
                                <a @click.prevent="isActivated = index" href="#"
                                   :class="{'active': isActivated === index}">
                                    <span>{{ option.title }}</span>
                                    <div class="icon"></div>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </section>
            </div>
            <div class="col-9">
                <div class="wookit-content" v-if="isActivated === index"
                     v-for="(option, index) in options">
                    <transition-group name="fade-up" target="div" appear>
                        <div class="content-tab" :key="index">
                            <div class="wookit-title">
                                <span>
                                    {{ option.title }}
                                </span>
                                <div class="wookit-description">
                                    {{ option.description }}
                                </div>
                            </div>
                            <div class="wookit-switch" v-if="option.tab1">
                                <div class="opt-col-6">
                                    <h3><?php echo __('Moving non-existent products to the bottom of the store list', 'dominokit') ?></h3>
                                    <p><?php echo __('If your template has this feature, please disable it', 'dominokit') ?></p>
                                </div>
                                <div class="opt-col-6 opt-title">
                                    <label class="switch">
                                        <input type="checkbox" @click="toggleCheckbox" v-model="checkbox">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="wookit-switch" v-if="option.tab1">
                                <div class="opt-col-6">
                                    <h3><?php echo __('Changing the default WooCommerce button (add to cart)', 'dominokit') ?></h3>
                                    <p><?php echo __('If your template has this feature, please disable it', 'dominokit') ?></p>
                                </div>
                                <div class="opt-col-6 opt-title">
                                    <label class="switch">
                                        <input type="checkbox" @click="toggleWooCart" v-model="wooCart">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                                <transition name="fade-up" target="div" appear>
                                    <div class="form-group btnWooCart" v-if="wooCart">
                                        <label for="txt-cart"><?php echo __('Add to cart button', 'dominokit') ?></label>
                                        <input class="form-control" type="text" id="txt-cart" v-model="txtBtnCart"
                                               placeholder="<?php echo __('Add to cart button text', 'dominokit') ?>"
                                               @blur="toggleWooCartTxt">
                                    </div>
                                </transition>
                            </div>

                            <div class="wookit-switch" v-if="option.tab2">
                                <div class="opt-col-6">
                                    <h3><?php echo __('Solarization of WooCommerce', 'dominokit') ?></h3>
                                    <p><?php echo __('Solarization of WooCommerce and WordPress dates', 'dominokit') ?></p>
                                </div>
                                <div class="opt-col-6 opt-title">
                                    <label class="switch">
                                        <input type="checkbox" @click="toggleShamsi" v-model="wooShamsi">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="wookit-switch" v-if="option.tab2">
                                <div class="opt-col-6">
                                    <h3><?php echo __('datepicker for WooCommerce', 'dominokit') ?></h3>
                                    <p><?php echo __('Adding the Persian calendar to WooCommerce', 'dominokit') ?></p>
                                </div>
                                <div class="opt-col-6 opt-title">
                                    <label class="switch">
                                        <input type="checkbox" @click="toggleDatepicker" v-model="wooDatepicker">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </transition-group>
                </div>
            </div>
        </div>
    </div>
</div>
