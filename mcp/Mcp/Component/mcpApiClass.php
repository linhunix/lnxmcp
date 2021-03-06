<?php
/**
 * LinHUniX Web Application Framework
 *
 * @author    Andrea Morello <lnxmcp@linhunix.com>
 * @copyright LinHUniX L.t.d., 2018, UK
 * @license   Proprietary See LICENSE.md
 * @version   GIT:2018-v2
 */

namespace LinHUniX\Mcp\Component;

use LinHUniX\Mcp\masterControlProgram;

/**
 * Core class for load modules
 */
 final class mcpApiClass
 {
     /**
      * Run Module as ToolApi Components
      * @param string $srvprc  name of the driver
      * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
      * @param array $scopeIn  Input Array with the value need to work
      * @param string $modinit Module name where is present the code and be load and initalized
      * @param string $subcall used if the name of the functionality ($callname) and the subcall are different
      * @return array $ScopeOut
      */
     public static function apiBase(masterControlProgram $mcp, $type, $srvprc, $pathsrc,$ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $mcptype="Api")
     {
         if ($vendor == null) {
             $vendor = $mcp->getDefApp();
         }
         $mcp->info("MCP>>" . $vendor . ">>".$type.">>" . $srvprc);
         if (! is_array($scopeIn)) {
             $scopeIn=array("In"=>$scopeIn);
         }
         $scopeIn["prev-output"] = ob_get_clean();
         return $mcp->module($srvprc, $pathsrc, $ispreload, $scopeIn, $modinit, $subcall, $vendor, $mcptype);
     }
     /**
      * Run Module as ToolApi Components and exit
      * @param string $srvprc  name of the driver
      * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
      * @param array $scopeIn  Input Array with the value need to work
      * @param string $modinit Module name where is present the code and be load and initalized
      * @param string $subcall used if the name of the functionality ($callname) and the subcall are different
      * @return array $ScopeOut
      */
     public static function apiArray(masterControlProgram $mcp, $srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $mcptype='Api')
     {
         return self::apiBase($mcp, "Api(A)",$srvprc, $mcp->getPathSrc(), $ispreload, $scopeIn, $modinit, $subcall, $vendor,$mcptype);
     }
     /**
      * Run Module as ToolApi Components and exit
      * @param string $srvprc  name of the driver
      * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
      * @param array $scopeIn  Input Array with the value need to work
      * @param string $modinit Module name where is present the code and be load and initalized
      * @param string $subcall used if the name of the functionality ($callname) and the subcall are different
      * @return void Exit
      */
     public static function api(masterControlProgram $mcp, $srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $mcptype='Api')
     {
         $res = self::apiBase($mcp, "Api",$srvprc, $mcp->getPathSrc(), $ispreload, $scopeIn, $modinit, $subcall, $vendor,$mcptype);
         ob_end_clean();
         header('Content-type: application/json');
         echo json_encode($res);
         exit();
     }
     /**
     * Run Module as ToolApi Components and exit
     * @param string $srvprc  name of the driver
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn  Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different
     * @return void Exit
     */
     public static function apiReturn(masterControlProgram $mcp, $srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $mcptype='Api')
     {
         $res = self::apiBase($mcp, "Api(Return)",$srvprc, $mcp->getPathSrc(), $ispreload, $scopeIn, $modinit, $subcall, $vendor,$mcptype);
         ob_end_clean();
         header('Content-type: application/json');
         if (isset($res["return"])) {
             echo json_encode($res["return"]);
         } else {
             echo "{}";
         }
         exit();
     }
     /**
     * Run Module as ToolApi Components and exit
     * @param string $srvprc  name of the driver
     * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
     * @param array $scopeIn  Input Array with the value need to work
     * @param string $modinit Module name where is present the code and be load and initalized
     * @param string $subcall used if the name of the functionality ($callname) and the subcall are different
     * @return void Exit
     */
     public static function apiCommon(masterControlProgram $mcp, $srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $mcptype='Api')
     {
         $res = self::apiBase($mcp, "Api(C)",$srvprc, $mcp->getPathMcp(), $ispreload, $scopeIn, $modinit, $subcall, $vendor, $mcptype);
         ob_end_clean();
         header('Content-type: application/json');
         echo json_encode($res);
         exit();
     }
     /**
      * Run Module as ToolApi Components and exit
      * @param string $srvprc  name of the driver
      * @param bool $ispreload is only a preload (ex page) or need to be execute (ex controller)
      * @param array $scopeIn  Input Array with the value need to work
      * @param string $modinit Module name where is present the code and be load and initalized
      * @param string $subcall used if the name of the functionality ($callname) and the subcall are different
      * @return array $ScopeOut
      */
     public static function apiCommonArray(masterControlProgram $mcp, $srvprc, $ispreload = false, $scopeIn = array(), $modinit = null, $subcall = null, $vendor = null, $mcptype='Api')
     {
         return self::apiBase($mcp, "Api(CA)",$srvprc, $mcp->getPathMcp(), $ispreload, $scopeIn, $modinit, $subcall, $vendor,$mcptype);
     }
 }
