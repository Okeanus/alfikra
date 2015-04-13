<?php 
session_start();
$thisPage="index";
if(isset($_SESSION["cart"])){

        //$_SESSION["cart"]["P_ID"]["quantity"] = $_POST["quant"];//<input type="hidden" name="quant" value=1></input>
}
else{
        $_SESSION["cart"] = [[]];
        $_SESSION["counter"] = 0;
}
include "includes/header.php"; 
?> 

<div id="content">
    <div class="index">
        <span style="text-shadow: rgba(255, 255, 255, 1) 0px 0px 4px;">
            <br />
                <p>Welcome to JemLine!</p>
                <br />
                <p>
                    As the #1 specialty jewelry brand, we know that offering fine jewelry at a great price is only part of the story. We are fully committed to providing a superior 
                    shopping experience. 
                    JemLine offers a fine selection of merchandise on the Internet at www.JemLine.com. JemLine provides its customers with the convenience of shopping online. 
                    Leveraging JemLine strong brand name, www.JemLine.com provides yet another channel to make a jewelry purchase an enjoyable experience.
                    <br />
                </p>
                <p>
                    Throughout its history, JemLine Jewelers have driven change and set standards in the jewelry industry. With its commitment to simplified credit options 
                    and convenient shopping through www.JemLine.com, Zales Jewelers continues to be the leader in fine jewelry retailing.
                    JemLine has been committed to providing our customers with unparalleled customer service, industry expertise and engagement rings that makes that special someone say, 
                    "I Am Loved." We're proud that customers like you trust JemLine with some of the most important diamond and jewelry purchases of your lives. 
                </p>
        </span>
    </div>
    <div id="indeximg"></div>
</div>


    
    
</body>



<?php 
include "includes/footer.php"; 
?> 
