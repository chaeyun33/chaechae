<?php
// includes/footer.php
if (!defined('_CUSTOM_')) exit;

// 기본 URL 설정 (정의되지 않은 경우)
if (!isset($base_url)) {
    $base_url = '';
}
?>

    <!--푸터 시작-->
    <footer>
      <div class="bt">
        <img src="<?php echo $base_url; ?>/img/btlogo.png" alt="아래로고">
        <ul class="cp">
          <li><a href="<?php echo $base_url; ?>/footer/policy.php">개인정보처리방침&nbsp;&nbsp;</a> | </li>
          <li><a href="<?php echo $base_url; ?>/footer/email-policy.php">이메일무단수집거부</a></li>
        </ul>
        <div class="imfor">(55011)전북특별자치도 전주시 완산구 아중로 33, D동 204호(중노송동)<br>
          Email. jeonbukwoorisai@gmail.com
          copyright(c) jeonbukwoorisai. ALL rights reserved</div>
      </div>
    </footer>
  </div>
  <!--푸터 끝-->
  
  <!--모바일 푸터 시작-->
  <div class="mbt">
    <img src="<?php echo $base_url; ?>/img/btlogo.png" alt="아래로고">
    <ul class="mcp">
      <li><a href="<?php echo $base_url; ?>/footer/policy.php">개인정보처리방침&nbsp;&nbsp;</a> | </li>
      <li><a href="<?php echo $base_url; ?>/footer/email-policy.php">이메일무단수집거부</a></li>
    </ul>
    <div class="mimfor">
      (55011)전북특별자치도 전주시 완산구 아중로 33, D동 204호(중노송동)<br>
      Email. jeonbukwoorisai@gmail.com<br>
      copyright(c) jeonbukwoorisai. ALL rights reserved.
    </div>
  </div>
  <!--모바일 푸터 끝-->

</body>
</html>