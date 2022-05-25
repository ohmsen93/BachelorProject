<?php
require __DIR__ . "/../config/config.php";

require_once ('../models/artist.php');

// Been fiddeling around with this, i cant seem to target phpunit correctly.
class ArtistTest extends \PHPUnit\Framework\TestCase {

    // The general thought here was to check the ammount of artists in the database vs a static number, mine is a bit lower as i have been testing the removal of artists.
    public function testGetArtistsFailingOrPassingDepends() {
        // Arrange
        $artist = new artist();

        // Act
        $actualArtists = $artist->getArtists();

        // Assert
        $this->assertSame(271,  count($actualArtists));

    }

    public function testGetArtistsbyidConnection(){
        // Arrange
        $artist = new artist();

        // Act
        $actualArtists = $artist->getArtists();

        // Assert
        $this->assertSame


    }






}


?>


