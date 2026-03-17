<div class="wiith">

    <header>
        <div class="content">
            <h1><?php _e( 'Why Is This Here?', 'why-is-it-here' ) ?></h1>
            <p>v<?php echo JP_WIIH_V ?></p>
        </div>
    </header>

    
    <main>
        <div class="content">
            <div class="box">
                <h2><?php _e( 'The antidote to WordPress plugin panic.', 'why-is-it-here' ) ?></h2>
                <p>You log into a client’s site and face a wall of 43 mysterious plugins. One is literally just called Bob's Thing. You hover over "Deactivate" in a cold sweat—if you click it, will the site explode? Seriously, who is Bob?! </p>
                <p>We’ve all been there. So, we built a digital sticky note for your plugins page.</p>
            </div>

            
            <div class="box stat-box">
                <h2><?php _e( 'Stats overview', 'why-is-it-here' ) ?></h2>

                <?php

                    $total_plugins = (int) $stats['total_plugins'];
                    $total_notes   = (int) $stats['active_with_notes'] + (int) $stats['inactive_with_notes'];
                    
                    $total_active_plugins = (int) $stats['total_active_plugins'];
                    $total_active_plugin_notes = (int) $stats['active_with_notes'];

                    $percent_all_notes    = ( $total_plugins > 0 ) ? round( ( $total_notes / $total_plugins ) * 100 ) : 0;
                    $percent_active_notes = ( $total_active_plugins > 0 ) ? round( ( $total_active_plugin_notes / $total_active_plugins ) * 100 ) : 0;

                ?>

                <div class="stats">
                    <div class="stat">
                        <span>Total plugins</span>
                        <div class="item-stat">
                            <p><?php echo esc_html( $total_plugins ); ?></p>
                            <b title="% of plugin with notes."><?php echo $percent_all_notes ?>%</b>
                        </div>
                    </div>
                    <div class="stat">
                        <span>Total active plugins</span>
                        <div class="item-stat">
                            <p><?php echo esc_html( $total_active_plugins ); ?></p>
                            <b title="% of plugin with notes."><?php echo $percent_active_notes ?>%</b>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <p>Protect your sanity. Document your plugins. <?php echo esc_html( $total_notes ) ?> of <?php echo esc_html( $total_plugins ) ?> plugin is documented.</p>
                    
                    <?php
                    // Determine the color class based on the percentage
                    $progress_class = 'wiih-bg-red'; // Default to red for 0-30%

                    if ( $percent_all_notes >= 71 ) {
                        $progress_class = 'wiih-bg-green';
                    } elseif ( $percent_all_notes >= 31 ) {
                        $progress_class = 'wiih-bg-orange';
                    }
                    ?>

                    <div class="wiih-progress-container">
                        <div class="wiih-progress-track">
                            <div class="wiih-progress-fill <?php echo esc_attr( $progress_class ); ?>" style="width: <?php echo esc_attr( $percent_all_notes ); ?>%;">
                                <!--  -->
                            </div>
                        </div>
                    </div>

                    <br>

                    <a href="<?php echo get_admin_url( null, 'plugins.php' ) ?>" class="button is-primary">Start adding notes</a>

                </div>

            </div>
        </div>
    </main>


</div>