<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $columnNames = config('permission.column_names');
        $teamForeignKey = $columnNames['team_foreign_key'] ?? 'team_id';
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $modelKey = $columnNames['model_morph_key'] ?? 'model_id';

        Schema::table('model_has_roles', function (Blueprint $table) use ($teamForeignKey, $pivotRole, $modelKey) {
            $table->dropPrimary();
            $table->unsignedBigInteger($teamForeignKey)->nullable()->change();
            $table->index([$teamForeignKey, $pivotRole, $modelKey, 'model_type'], 'model_has_roles_role_model_type_index');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) use ($teamForeignKey, $pivotPermission, $modelKey) {
            $table->dropPrimary();
            $table->unsignedBigInteger($teamForeignKey)->nullable()->change();
            $table->index([$teamForeignKey, $pivotPermission, $modelKey, 'model_type'], 'model_has_permissions_permission_model_type_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columnNames = config('permission.column_names');
        $teamForeignKey = $columnNames['team_foreign_key'] ?? 'team_id';
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $modelKey = $columnNames['model_morph_key'] ?? 'model_id';

        Schema::table('model_has_roles', function (Blueprint $table) use ($teamForeignKey, $pivotRole, $modelKey) {
            $table->dropIndex('model_has_roles_role_model_type_index');
            $table->unsignedBigInteger($teamForeignKey)->nullable(false)->change();
            $table->primary([$teamForeignKey, $pivotRole, $modelKey, 'model_type'], 'model_has_roles_role_model_type_primary');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) use ($teamForeignKey, $pivotPermission, $modelKey) {
            $table->dropIndex('model_has_permissions_permission_model_type_index');
            $table->unsignedBigInteger($teamForeignKey)->nullable(false)->change();
            $table->primary([$teamForeignKey, $pivotPermission, $modelKey, 'model_type'], 'model_has_permissions_permission_model_type_primary');
        });
    }
};
