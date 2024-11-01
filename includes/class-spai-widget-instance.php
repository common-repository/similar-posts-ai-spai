<?php

class WidgetInstance {

    private $showOn = null;
    private $display_type = null;
    private $heading = null;
    private $default_image = null;
    private $template = null;
    private $title_max = null;
    private $effect_of_increasing_the_image_size = false;
    private $show_category = false;
    private $heading_color = null;
    private $category_color = null;
    private $category_background_color = null;

    public function __construct($instance = [])
    {
        $this->import($instance);
    }

    /**
     * @return integer|null
     */
    public function getShowOn()
    {
        return $this->showOn;
    }

    /**
     * @param integer|null $showOn
     */
    public function setShowOn($showOn)
    {
        $this->showOn = $showOn;
    }

    /**
     * @return integer|null
     */
    public function getDisplayType()
    {
        return $this->display_type;
    }

    /**
     * @param integer|null $display_type
     */
    public function setDisplayType($display_type)
    {
        $this->display_type = $display_type;
    }

    /**
     * @return string|null
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * @param string|null $heading
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;
    }

    /**
     * @return string|null
     */
    public function getDefaultImage()
    {
        return $this->default_image;
    }

    /**
     * @param string|null $default_image
     */
    public function setDefaultImage($default_image)
    {
        $this->default_image = $default_image;
    }

    /**
     * @return integer|null
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param integer|null $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return integer|null
     */
    public function getTitleMax()
    {
        return $this->title_max;
    }

    /**
     * @param integer|null $title_max
     */
    public function setTitleMax($title_max)
    {
        $this->title_max = $title_max;
    }

    /**
     * @return bool|null
     */
    public function getEffectOfIncreasingTheImageSize()
    {
        return $this->effect_of_increasing_the_image_size;
    }

    /**
     * @param bool|null $effect_of_increasing_the_image_size
     */
    public function setEffectOfIncreasingTheImageSize($effect_of_increasing_the_image_size)
    {
        if (
            $effect_of_increasing_the_image_size === '1'
            || $effect_of_increasing_the_image_size === 'true'
            || $effect_of_increasing_the_image_size === 1
            || $effect_of_increasing_the_image_size === true
        ) {
            $effect_of_increasing_the_image_size = true;
        } else {
            $effect_of_increasing_the_image_size = false;
        }
        $this->effect_of_increasing_the_image_size = $effect_of_increasing_the_image_size;
    }

    /**
     * @return bool|null
     */
    public function getShowCategory()
    {
        return $this->show_category;
    }

    /**
     * @param bool|null $show_category
     */
    public function setShowCategory($show_category)
    {
        if (
            $show_category === '1'
            || $show_category === 'true'
            || $show_category === 1
            || $show_category === true
        ) {
            $show_category = true;
        } else {
            $show_category = false;
        }

        $this->show_category = $show_category;
    }

    /**
     * @return string|null
     */
    public function getHeadingColor()
    {
        return $this->heading_color;
    }

    /**
     * @param string|null $heading_color
     */
    public function setHeadingColor($heading_color)
    {
        $this->heading_color = $heading_color;
    }

    /**
     * @return string|null
     */
    public function getCategoryColor()
    {
        return $this->category_color;
    }

    /**
     * @param string|null $category_color
     */
    public function setCategoryColor($category_color)
    {
        $this->category_color = $category_color;
    }

    /**
     * @return string|null
     */
    public function getCategoryBackgroundColor()
    {
        return $this->category_background_color;
    }

    /**
     * @param string|null $category_background_color
     */
    public function setCategoryBackgroundColor($category_background_color)
    {
        $this->category_background_color = $category_background_color;
    }

    public function import($instance = [])
    {
        $showOn = isset($instance['showOn']) ? (bool)$instance['showOn'] : null;
        $heading = isset($instance['heading']) ? $instance['heading'] : null;
        $default_image = isset($instance['default_image']) ? $instance['default_image'] : null;
        $display_type = isset($instance['display_type']) ? (int)$instance['display_type'] : 5;
        $template = isset($instance['template']) ? (int)$instance['template'] : 2;
        $title_max = isset($instance['title_max']) ? (int)$instance['title_max'] : 50;
        $effect_of_increasing_the_image_size = isset($instance['effect_of_increasing_the_image_size']) ? $instance['effect_of_increasing_the_image_size'] : false;
        $show_category = isset($instance['show_category']) ? $instance['show_category'] : false;
        $heading_color = isset($instance['heading_color']) ? $instance['heading_color'] : null;
        $category_color = isset($instance['category_color']) ? $instance['category_color'] : null;
        $category_background_color = isset($instance['category_background_color']) ? $instance['category_background_color'] : null;

        $this->setShowOn($showOn);
        $this->setDisplayType($display_type);
        $this->setHeading($heading);
        $this->setDefaultImage($default_image);
        $this->setTemplate($template);
        $this->setTitleMax($title_max);
        $this->setShowCategory($show_category);
        $this->setHeadingColor($heading_color);
        $this->setCategoryColor($category_color);
        $this->setCategoryBackgroundColor($category_background_color);
        $this->setEffectOfIncreasingTheImageSize($effect_of_increasing_the_image_size);
    }
}
