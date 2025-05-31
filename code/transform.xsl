<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
      xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" encoding="UTF-8" indent="yes"/>

  <xsl:template match="/">
    <html lang="cs">
      <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>AniShelf - Seznam titulů</title>
        <link rel="stylesheet" href="styles.css"/>
      </head>
      <body>

        <!-- Logo -->
        <div id="logo">
          <a href="index.php">
            <img src="images/anishelf_logo.png" width="200" height="200" alt="AniShelf logo"/>
          </a>
        </div>

        <!-- Navigace -->
        <nav>
          <div class="nav-inner">
            <a href="index.php">Domů</a>
            <a href="anime.php">Anime</a>
            <a href="manga.php">Manga</a>
          </div>
        </nav>

        <!-- Obsah -->
        <div id="content">
          <h1>Seznam titulů</h1>

          <!-- Sekce Anime -->
          <xsl:if test="titles/title[@category='anime']">
            <h2 class="section-title">Anime</h2>
            <xsl:for-each select="titles/title[@category='anime']">
              <div class="title-card">
                <img src="images/{image}" alt="{name}"/>
                <div class="title-info">
                  <a href="title.php?slug={slug}">
                    <xsl:value-of select="name"/>
                  </a><br/>
                  <div class="details">
                    <xsl:value-of select="type"/> (<xsl:value-of select="episodes"/> eps)
                  </div>
                </div>
              </div>
            </xsl:for-each>
          </xsl:if>

          <!-- Sekce Manga -->
          <xsl:if test="titles/title[@category='manga']">
            <h2 class="section-title">Manga</h2>
            <xsl:for-each select="titles/title[@category='manga']">
              <div class="title-card">
                <img src="images/{image}" alt="{name}"/>
                <div class="title-info">
                  <a href="title.php?slug={slug}">
                    <xsl:value-of select="name"/>
                  </a><br/>
                  <div class="details">
                    <xsl:value-of select="type"/>
                  </div>
                </div>
              </div>
            </xsl:for-each>
          </xsl:if>
        </div>

      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
