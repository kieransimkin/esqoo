<?php
class Picture extends DBSQL { 
	public static $_cachedfields=array('digital_negative__asset_id','web_small__asset_id','web_medium__asset_id','web_large__asset_id','web_fullsize__asset_id','thumbnail_small__asset_id','thumbnail_large__asset_id','square__asset_id');
	public static function assert_picture_size_type($type) { 
		if ($type!='web-fullsize' && $type!='web-small' && $type!='web-medium' && $type!='web-large' && $type!='thumbnail-large' && $type!='thumbnail-small' && $type!='square') { 
			throw new Exception('type must be one of web-small, web-medium, web-large, thumbnail-large, thumbnail-small or square');
		}
	}
	public function get_raw_exif_data() { 
		$fname=$this->digital_negative__asset->get_filename();
		return exif_read_data($fname);
	}
	public function get_exif_exposure_program($p) { 
		$text='';
		switch($p) { 
			case 1:
				$text=_('Manual');
				break;
			case 2:
				$text=_('Normal');
				break;
			case 3:
				$text=_('Aperture Priority');
				break;
			case 4:
				$text=_('Shutter Priority');
				break;
			case 5:
				$text=_('Creative Program (Biased towards wide depth-of-field)');
				break;
			case 6:
				$text=_('Action Program (Biased towards fast shutter speed)');
				break;
			case 7:
				$text=_('Portrait Mode (Biased towards shallow depth-of-field)');
				break;
			case 8:
				$text=('Landscape Mode (Background in focus)');
				break;
			default:
				$text=_('Unknown');
			
		}
		return $text;
	}
	public function get_exif_orientation($o) { 
		$text='hi';
		switch($exif['Orientation']) { 
			case 1:
				// The 0th row is at the visual top of the image, and the 0th column is the visual left-hand side.
				break;
			case 2:
				// The 0th row is at the visual top of the image, and the 0th column is the visual right-hand side.
				break;

			case 3:
				// The 0th row is at the visual bottom of the image, and the 0th column is the visual right-hand side.

				break;
			case 4:
				// The 0th row is at the visual bottom of the image, and the 0th column is the visual left-hand side.
				break;
			case 5: 
				// The 0th row is the visual left-hand side of the image, and the 0th column is the visual top.
				break;
			case 6: 
				// The 0th row is the visual right-hand side of the image, and the 0th column is the visual top.
				break;
			case 7:
				// The 0th row is the visual right-hand side of the image, and the 0th column is the visual bottom.
				break;
			case 8: 
				// The 0th row is the visual left-hand side of the image, and the 0th column is the visual bottom.

				break;

		}
		return $text;
	}
	public function get_exif_flash($f) { 
		$text='';
		switch($f) { 
			case 0x00:
				$text=_('Flash did not fire');
				break;
			case 0x01:
				$text=_('Flash fired');
				break;
			case 0x05:
				$text=_('Strobe return light not detected');
				break;
			case 0x07:
				$text=_('Strobe return light detected');
				break;
			case 0x09:
				$text=_('Flash fired, compulsory flash mode');
				break;
			case 0x0D:
				$text=_('Flash fired, compulsory flash mode, return light not detected');
				break;
			case 0x0F:
				$text=_('Flash fired, compulsory flash mode, return light detected');
				break;
			case 0x10:
				$text=_('Flash did not fire, compulsory flash mode');
				break;
			case 0x18:
				$text=_('Flash did not fire, auto mode');
				break;
			case 0x19:
				$text=_('Flash fired, auto mode');
				break;
			case 0x1D:
				$text=_('Flash fired, auto mode, return light not detected');
				break;
			case 0x1F:
				$text=_('Flash fired, auto mode, return light detected');
				break;
			case 0x20:
				$text=_('No flash function');
				break;
			case 0x41:
				$text=_('Flash fired, red-eye reduction mode');
				break;
			case 0x45:
				$text=_('Flash fired, red-eye reduction mode, return light not detected');
				break;
			case 0x47:
				$text=_('Flash fired, red-eye reduction mode, return light detected');
				break;
			case 0x49:
				$text=_('Flash fired, compulsory flash mode, red-eye reduction mode');
				break;
			case 0x4D:
				$text=_('Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected');
				break;
			case 0x4F:
				$text=_('Flash fired, compulsory flash mode, red-eye reduction mode, return light detected');
				break;
			case 0x59:
				$text=_('Flash fired, auto mode, red-eye reduction mode');
				break;
			case 0x5D:
				$text=_('Flash fired, auto mode, return light not detected, red-eye reduction mode');
				break;
			case 0x5F:
				$text=_('Flash fired, auto mode, return light detected, red-eye reduction mode');
				break;
			default:
				$text=_('Unknown');
		}
		return $text;
	}
	public function get_exif_metering_mode($m) { 
		$text='';
		switch($m) { 
			case 1:
				$text=_('Average');
				break;
			case 2:
				$text=_('Centre Weighted Average');
				break;
			case 3:
				$text=_('Spot');
				break;
			case 4:
				$text=_('Multi-spot');
				break;
			case 5:
				$text=_('Pattern');
				break;
			case 6:
				$text=_('Partial');
				break;
			default:
				$text=_('Unknown');
		}	
		return $text;
	}
	private function get_exif_afga_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	private function get_exif_canon_makernotes($exif) { 
		$ret=array();
		$ret['Lens']=$exif['UndefinedTag:0x0095'];
		return $ret;
	}
	private function get_exif_casio_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	private function get_exif_epson_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	private function get_exif_fujifilm_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	private function get_exif_konica_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	private function get_exif_kyocera_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	private function get_exif_nikon_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	private function get_exif_olympus_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	private function get_exif_panasonic_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	private function get_exif_pentax_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	private function get_exif_ricoh_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	private function get_exif_sony_makernotes($exif) { 
		$ret=array();
			
		return $ret;
	}
	public function get_exif_data() { 
		$ret=array();
		$exif=$this->get_raw_exif_data();
		$ret['ApertureFNumber']=$exif['COMPUTED']['ApertureFNumber'];
		$ret['ExposureTime']=$exif['ExposureTime'];
		$ret['CCDWidth']=$exif['COMPUTED']['CCDWidth'];
		$ret['IsColor']=$exif['COMPUTED']['IsColor'];
		$ret['Make']=$exif['Make'];
		switch ($exif['Make']) { 
			case 'Agfa':
				$makernotes=$this->get_exif_afga_makernotes($exif);
				break;
			case 'Canon':
				$makernotes=$this->get_exif_canon_makernotes($exif);
				break;
			case 'Casio':
				$makernotes=$this->get_exif_casio_makernotes($exif);
				break;
			case 'Epson':
				$makernotes=$this->get_exif_epson_makernotes($exif);
				break;
			case 'Fujifilm':
				$makernotes=$this->get_exif_fujifilm_makernotes($exif);
				break;
			case 'Konica':
				$makernotes=$this->get_exif_konica_makernotes($exif);
				break;
			case 'Kyocera':
				$makernotes=$this->get_exif_kyocera_makernotes($exif);
				break;
			case 'Nikon':
				$makernotes=$this->get_exif_nikon_makernotes($exif);
				break;
			case 'Olympus':
				$makernotes=$this->get_exif_olympus_makernotes($exif);
				break;
			case 'Panasonic':
				$makernotes=$this->get_exif_panasonic_makernotes($exif);
				break;
			case 'Pentax':
				$makernotes=$this->get_exif_pentax_makernotes($exif);
				break;
			case 'Ricoh':
				$makernotes=$this->get_exif_ricoh_makernotes($exif);
				break;
			case 'Sony':
				$makernotes=$this->get_exif_sony_makernotes($exif);
				break;
		}
		$ret['MakerNotes']=$makernotes;
		$ret['Model']=$exif['Model'];
		$ret['OrientationText']=$this->get_exif_orientation($exif['Orientation']);
		$ret['Orientation']=$exif['Orientation'];
		$ret['DateTime']=$exif['DateTime'];
		$ret['DateTimeOriginal']=$exif['DateTimeOriginal'];
		$ret['DateTimeDigitized']=$exif['DateTimeDigitized'];
		$ret['ISOSpeedRatings']=$exif['ISOSpeedRatings'];
		$ret['ExposureProgramText']=$this->get_exif_exposure_program($exif['ExposureProgram']);		
		$ret['ExposureProgram']=$exif['ExposureProgram'];
		if ($exif['ExposureMode']==0) { 
			$ret['ExposureModeText']=_('Auto');
		} else if ($exif['ExposureMode']==1) { 
			$ret['ExposureModeText']=_('Manual');
		} else if ($exif['ExposureMode']==2) { 
			$ret['ExposureModeText']=_('Auto bracket');
		}
		$ret['ExposureMode']=$exif['ExposureMode'];
		$ret['ExposureBiasValue']=$exif['ExposureBiasValue'];
		$ret['MeteringModeText']=$this->get_exif_metering_mode($exif['MeteringMode']);
		$ret['MeteringMode']=$exif['MeteringMode'];
		$ret['FlashText']=$this->get_exif_flash($exif['Flash']);
		$ret['Flash']=$exif['Flash'];
		if (substr($exif['FocalLength'],-2,2)=='/1') { 
			$ret['FocalLength']=substr($exif['FocalLength'],0,strlen($exif['FocalLength'])-2);
		} else { 
			$ret['FocalLength']=$exif['FocalLength'];
		}
		$ret['GPSLatitude']=$exif['GPSLatitude'];
		$ret['GPSLatitudeRef']=$exif['GPSLatitudeRef'];
		$ret['GPSLongitude']=$exif['GPSLongitude'];
		$ret['GPSLongitudeRef']=$exif['GPSLongitudeRef'];
		$ret['GPSLatitudeDecimal']=self::get_exif_gps($exif['GPSLatitude'],$exif['GPSLatitudeRef']);
		$ret['GPSLongitudeDecimal']=self::get_exif_gps($exif['GPSLongitude'],$exif['GPSLongitudeRef']);
		$ret['GPSAltitude']=$exif['GPSAltitude'];
		$ret['GPSAltitudeRef']=$exif['GPSAltitudeRef'];
		if ($exif['GPSAltitudeRef']) { 
			$ret['GPSAltitudeDecimal']=-1*self::exif_gps2Num($exif['GPSAltitude']);
		} else {
			$ret['GPSAltitudeDecimal']=self::exif_gps2Num($exif['GPSAltitude']);
		}
		$ret['GPSDOP']=self::exif_gps2Num($exif['GPSDOP']);
		$ret['GPSSpeed']=self::exif_gps2Num($exif['GPSSpeed']);
		$ret['GPSSpeedRef']=$exif['GPSSpeedRef'];
		$ret['GPSImgDirection']=self::exif_gps2Num($exif['GPSImgDirection']);
		$ret['GPSImgDirectionRef']=$exif['GPSImgDirectionRef'];
		$ret['GPSDestLatitude']=$exif['GPSDestLatitude'];
		$ret['GPSDestLatitudeRef']=$exif['GPSDestLatitudeRef'];
		$ret['GPSDestLongitude']=$exif['GPSDestLongitude'];
		$ret['GPSDestLongitudeRef']=$exif['GPSDestLongitudeRef'];
		$ret['GPSDestLatitudeDecimal']=self::get_exif_gps($exif['GPSDestLatitude'],$exif['GPSDestLatitudeRef']);
		$ret['GPSDestLongitudeDecimal']=self::get_exif_gps($exif['GPSDestLongitude'],$exif['GPSDestLongitudeRef']);
		$ret['GPSDestBearing']=self::exif_gps2Num($exif['GPSDestBearing']);
		$ret['GPSDestBearingRef']=$exif['GPSDestBearingRef'];
		$ret['GPSDestDistance']=self::exif_gps2Num($exif['GPSDestDistance']);
		$ret['GPSDestDistanceRef']=$exif['GPSDestDistanceRef'];
		$ret['FocusDistance']=$exif['COMPUTED']['FocusDistance'];
		return $ret;
	}
	private static function get_exif_gps($exifCoord, $hemi) {

	    $degrees = count($exifCoord) > 0 ? self::exif_gps2Num($exifCoord[0]) : 0;
	    $minutes = count($exifCoord) > 1 ? self::exif_gps2Num($exifCoord[1]) : 0;
	    $seconds = count($exifCoord) > 2 ? self::exif_gps2Num($exifCoord[2]) : 0;

	    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

	    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);

	}
	private static function exif_gps2Num($coordPart) {
	    $parts = explode('/', $coordPart);

	    if (count($parts) <= 0)
		return 0;

	    if (count($parts) == 1)
		return $parts[0];

	    return floatval($parts[0]) / floatval($parts[1]);
	}

	public function generate_thumbnail($size) { 
		$max=$this->album->user->get_picture_size($size);
		if ($size=='square' || substr($size,0,10)=='thumbnail_') { 
			$fname=$this->thumbnail_fullsize__asset->get_filename();
		} else { 
			$fname=$this->web_fullsize__asset->get_filename();
		}
		$image=new Imagick($fname);
		$image->thumbnailImage($max,$max,true);
		$image->setImageFormat('jpeg');
	        $image->setImageCompressionQuality(90);
		$asset=Asset::get();
		$data=(string)$image;
		$image->destroy();
		$asset->Size=strlen($data);
		$asset->ChunkSize=strlen($data);
		$asset->MimeType='image/jpeg';
		$asset->HashType='MD5';
		$asset->AssetHash=md5($data);
		$assetid=$asset->save();
		$fp=fopen($asset->get_filename(),"wb");
		fwrite($fp,$data);
		fclose($fp);
		$var=str_replace('-','_',$size).'__asset_id';
		$this->$var=$assetid;
		return $this->save();
	}
	public function get_url($size='web-small') { 
		self::assert_picture_size_type($size);
		return '/asset/picture/'.$size.'/'.$this->id.'-'.Helper::url_friendly_encode($this->Name);
	}
	public function get_url_array() { 
		$ret=array();
		foreach (array('web-fullsize','web-small','web-medium','web-large','thumbnail-large','thumbnail-small','square') as $type) { 
			$ret[$type]=$this->get_url($type);
		}
		return $ret;
	}
	public function import_raw() { 

	}
} 
