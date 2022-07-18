<?php

if ( ! function_exists('photo_url') || ! function_exists('gallery_url'))
{
	/**
	 * Photo URL
	 *
	 * @param	string|int	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function photo_url($id_content, $title, $protocol = NULL)
	{
		return site_url("hypephoto/{$id_content}/" . strtolower(url_title($title)), $protocol);
	}

	function gallery_url($id_content, $type, $title, $protocol = NULL)
	{
		return site_url("hypevirtual/show/{$id_content}/{$type}/" . strtolower(url_title($title)), $protocol);
	}
}

