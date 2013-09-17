<?php

class sfWidgetFormSchemaFormatterDiv extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = "<div class=\"field\">\n  %error%%label%\n  %field%%help%\n%hidden_fields%</div>\n",
    $errorRowFormat  = "<div class=\"errors\">\n%errors%</div>\n",
    $helpFormat      = '<div class=\"help">%help%</div>',
    $decoratorFormat = "";
}
