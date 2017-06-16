<?php
/**
 * Retrieve site will display fully detailed information about a track,
 * including it's sub-courses
 */
?>
<article class="ipWidget">
    <em>Track</em>
    <section style="display: flex; justify-content: space-between">
        <h2><?=$track['title']?></h2>
        <strong>Price: <?=$track['price']?> €</strong>
    </section>

    <em>About this track</em>
    <p class="introduction"><?=$track['long_description']?></p>

    <section>
        <h2>Courses</h2>
        <small>Bellow is the included courses for each track</small>

        <ul>
            <li>
                <h3>Course 1</h3>
                <p>bla bla bla bla</p>
                <a href="#">Watch course</a>
            </li>

            <li>
                <h3>Course 2</h3>
                <p>bla bla bla bla</p>
                <a href="#">Watch course</a>
            </li>

            <li>
                <h3>Course 3</h3>
                <p>bla bla bla bla</p>
                <a href="#">Watch course</a>
            </li>
        </ul>

    </section>
</article>