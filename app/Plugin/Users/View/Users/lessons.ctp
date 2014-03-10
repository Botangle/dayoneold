<!--Wrapper HomeServices Block Start Here-->
<?php
echo $this->element("breadcrame",array('breadcrumbs'=>
array(__("My Lesson")=>__("My Lesson")))
);?>


<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">

            </div>
        </div>
        <div class="row-fluid">
            <?php echo $this->Element("myaccountleft") ?>
            <div class="span9">
                <h2 class="page-title">Lesson
                    <p class="pull-right">
                        <button class="btn btn-primary btn-primary3" type="submit">+ Add New Lesson</button>
                    </p>
                </h2>
                <div class="StaticPageRight-Block">
                    <div class="PageLeft-Block">
                        <p class="FontStyle20 color1">Active Lesson Proposal</p>

                        <div class="Lesson-row active">
                            <div class="row-fluid">
                                <div class="span1 tutorimg"><img src="<?php echo $this->webroot?>images/thumb-typ1.png"
                                                                 class="img-circle" alt="img"></div>
                                <div class="span2 tutor-name"><a href="#">Simon G.</a></div>
                                <div class="span1 date"> Dec 9</div>
                                <div class="span1 time"> 9:00 PM PST</div>
                                <div class="span1 mins"> 30 mins</div>
                                <div class="span2 subject"> C++</div>
                                <div class="span2 message"> Confirmed</div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Mark As Read</button>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="PageLeft-Block">
                        <p class="FontStyle20 color1">Upcoming Lessons</p>

                        <div class="Lesson-row">
                            <div class="row-fluid">
                                <div class="span1 tutorimg"><img src="<?php echo $this->webroot?>images/thumb-typ1.png"
                                                                 class="img-circle" alt="img"></div>
                                <div class="span2 tutor-name"><a href="#">Simon G.</a></div>
                                <div class="span1 date"> Dec 9</div>
                                <div class="span1 time"> 9:00 PM PST</div>
                                <div class="span1 mins"> 30 mins</div>
                                <div class="span2 subject"> C++</div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Change</button>
                                </div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Go To Lesson</button>
                                </div>
                            </div>
                        </div>
                        <div class="Lesson-row">
                            <div class="row-fluid">
                                <div class="span1 tutorimg"><img src="<?php echo $this->webroot?>images/thumb-typ1.png"
                                                                 class="img-circle" alt="img"></div>
                                <div class="span2 tutor-name"><a href="#">Simon G.</a></div>
                                <div class="span1 date"> Dec 9</div>
                                <div class="span1 time"> 9:00 PM PST</div>
                                <div class="span1 mins"> 30 mins</div>
                                <div class="span2 subject"> C++</div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Change</button>
                                </div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Go To Lesson</button>
                                </div>
                            </div>
                        </div>
                        <div class="Lesson-row">
                            <div class="row-fluid">
                                <div class="span1 tutorimg"><img src="<?php echo $this->webroot?>images/thumb-typ1.png"
                                                                 class="img-circle" alt="img"></div>
                                <div class="span2 tutor-name"><a href="#">Simon G.</a></div>
                                <div class="span1 date"> Dec 9</div>
                                <div class="span1 time"> 9:00 PM PST</div>
                                <div class="span1 mins"> 30 mins</div>
                                <div class="span2 subject"> C++</div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Change</button>
                                </div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Go To Lesson</button>
                                </div>
                            </div>
                        </div>

                        <div class="Lesson-row">
                            <div class="row-fluid">
                                <div class="span1 tutorimg"><img src="<?php echo $this->webroot?>images/thumb-typ1.png"
                                                                 class="img-circle" alt="img"></div>
                                <div class="span2 tutor-name"><a href="#">Simon G.</a></div>
                                <div class="span1 date"> Dec 9</div>
                                <div class="span1 time"> 9:00 PM PST</div>
                                <div class="span1 mins"> 30 mins</div>
                                <div class="span2 subject"> C++</div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Change</button>
                                </div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Go To Lesson</button>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="PageLeft-Block">
                        <p class="FontStyle20 color1">Past Lessons</p>

                        <div class="Lesson-row">
                            <div class="row-fluid">
                                <div class="span1 tutorimg"><img src="<?php echo $this->webroot?>images/thumb-typ1.png"
                                                                 class="img-circle" alt="img"></div>
                                <div class="span2 tutor-name"><a href="#">Simon G.</a></div>
                                <div class="span1 date"> Dec 9</div>
                                <div class="span1 time"> 9:00 PM PST</div>
                                <div class="span1 mins"> 30 mins</div>
                                <div class="span2 subject"> C++</div>
                                <div class="span2 mark"><p>Rating: <input type="number" name="your_awesome_parameter"
                                                                          id="some_id" class="rating"
                                                                          data-clearable="remove"/></p></div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Go To Lesson</button>
                                </div>
                            </div>
                        </div>
                        <div class="Lesson-row">
                            <div class="row-fluid">
                                <div class="span1 tutorimg"><img src="<?php echo $this->webroot?>images/thumb-typ1.png"
                                                                 class="img-circle" alt="img"></div>
                                <div class="span2 tutor-name"><a href="#">Simon G.</a></div>
                                <div class="span1 date"> Dec 9</div>
                                <div class="span1 time"> 9:00 PM PST</div>
                                <div class="span1 mins"> 30 mins</div>
                                <div class="span2 subject"> C++</div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Review</button>
                                </div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Go To Lesson</button>
                                </div>
                            </div>
                        </div>
                        <div class="Lesson-row">
                            <div class="row-fluid">
                                <div class="span1 tutorimg"><img src="<?php echo $this->webroot?>images/thumb-typ1.png"
                                                                 class="img-circle" alt="img"></div>
                                <div class="span2 tutor-name"><a href="#">Simon G.</a></div>
                                <div class="span1 date"> Dec 9</div>
                                <div class="span1 time"> 9:00 PM PST</div>
                                <div class="span1 mins"> 30 mins</div>
                                <div class="span2 subject"> C++</div>
                                <div class="span2 mark"><p>Rating: <input type="number" name="your_awesome_parameter"
                                                                          id="some_id" class="rating"
                                                                          data-clearable="remove"/></p></div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Go To Lesson</button>
                                </div>
                            </div>
                        </div>

                        <div class="Lesson-row">
                            <div class="row-fluid">
                                <div class="span1 tutorimg"><img src="<?php echo $this->webroot?>images/thumb-typ1.png"
                                                                 class="img-circle" alt="img"></div>
                                <div class="span2 tutor-name"><a href="#">Simon G.</a></div>
                                <div class="span1 date"> Dec 9</div>
                                <div class="span1 time"> 9:00 PM PST</div>
                                <div class="span1 mins"> 30 mins</div>
                                <div class="span2 subject"> C++</div>
                                <div class="span2 mark"><p>Rating: <input type="number" name="your_awesome_parameter"
                                                                          id="some_id" class="rating"
                                                                          data-clearable="remove"/></p></div>
                                <div class="span2 mark">
                                    <button class="btn btn-primary btn-primary3" type="submit">Go To Lesson</button>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
        <!-- @end .row -->


    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here--> 