                        <?php
                        $i = 1;
                        if (!empty($users)) {
                            foreach ($users as $user) {
                                ?>
                                <div class="span4 search-result-box">
                                    <div class="search-result-img">
                                        <a href="{{ url('user') .'/'. $user->username }}">
                                            {{ Html::image(url($user->picture), 'tutor', array('class' => 'img-circle', 'style' => 'width: 117px; height: 117px')) }}
                                        </a>
                                    </div>
                                    <div class="search-result-options">
                                        <div class="pull-left"><input type="number" name="your_awesome_parameter" id="some_id" class="rating" data-clearable="0" value="{{ $user->rating_stars }}"/></div>
                                        <div class="search-result-chat pull-right">
                                            <p class="option-pro">
                                                {{ Html::link(route('user.profile', $user->username), '', array('data-toggle' => 'tooltip', 'title' => 'Profile')) }}
                                            </p>
                                            <p class="option-msg">
                                                {{ Html::link(route('user.messages', $user->username), '', array('data-toggle' => 'Message', 'title' => 'Message')) }}
                                            </p>

                                        </div>
                                    </div>
                                    <div class="search-result-title">
                                        <p class="FontStyle20">
                                            {{ Html::link(route('user.profile', $user->username), $user->fullName, array('title' => $user->username)) }}
                                        </p>
                                    </div>
                                    <div class="search-result-details">
                                            <span>
                                                {{ implode("<br><br>", $user->getSummaryCardDetailsArray()) }}
                                            </span>
                                    </div>
                                </div>

                                <?php
                                if ($i % 3 == 0) {
                                    echo '</div>  <div class="row-fluid">';
                                }
                                $i++;
                            }
                        }
                        ?>
