<?xml version='1.0'?>
<xsl:stylesheet 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
    xmlns:content="http://purl.org/rss/1.0/modules/content/" version="1.0" 
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/" >
    
    <xsl:output
        omit-xml-declaration="no" method="html"
        doctype-public="-//W3C//DTD HTML 4.01//EN"
        doctype-system=
        "http://www.w3.org/TR/html4/strict.dtd"
        indent="yes" encoding="UTF-8"/>
    
    <xsl:strip-space elements="*"/>
    
    <!-- param values may be changed during the XSL Transformation -->
    <xsl:param name="title">
        Princeton University Digital Library -- Collection Overview
    </xsl:param>
    
    <xsl:template match="/">
        
        <html>
            
            <head>
                <!--<title>
                    <xsl:value-of select="$title" />
                </title>-->
                <meta charset="utf-8"/>
                <title>Finding Aids - Help</title>
                
                <!--[if IE]>
                    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
                    <![endif]-->
                <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js">#</script>
                <script type="text/javascript" src="bootstrap-tab.js">#</script>
                <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
                <link href="override.css" rel="stylesheet" media="screen, projection" />
            </head>
            <body>
                <div style="float:left;width:190px;margin-top:10px;">
                    <ul class="nav nav-tabs nav-stacked" id="helpTab">
                        <xsl:apply-templates select="//item" mode="tabs"/>
                    </ul>
                </div>
                <div class="tab-content" style="margin-left:190px;padding:10px;width:370px;">
                    <xsl:apply-templates select="//item" mode="content"/>
                    
                    <script type="text/javascript">
                      $(function () {
                        $('#helpTab a:first').tab('show');
                      })
                    </script>
                </div>
            </body>
        </html>
    </xsl:template>
    
    <xsl:template match="//item" mode="tabs">
        <li>
            <xsl:element name="a">
                <xsl:attribute name="href">
                    <xsl:text>#</xsl:text><xsl:value-of select="translate(title, ' ', '')"/>
                </xsl:attribute>
                <xsl:attribute name="data-toggle">tab</xsl:attribute>
                <xsl:value-of select="title"/>
            </xsl:element>
        </li>
    </xsl:template>
    
    <xsl:template match="//item" mode="content">
        <div class="tab-pane" id="{translate(title, ' ', '')}"><h3><xsl:value-of select="title"/></h3><xsl:value-of disable-output-escaping="yes" select="content:encoded"/></div>
    </xsl:template>
    
</xsl:stylesheet>
